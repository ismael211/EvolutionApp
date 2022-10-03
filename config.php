<!-- funcao abre modal processando -->
<script>
    function processando(faca) {
        if (faca == "1") {
            $.blockUI({
                message: '<div class="spinner-border text-primary" role="status"></div>',
                css: {
                    backgroundColor: 'transparent',
                    border: '0'
                },
                overlayCSS: {
                    backgroundColor: '#fff',
                    opacity: 0.8
                }
            });

        }
        if (faca == "0") {
            $.blockUI({
                message: '<div class="spinner-border text-primary" role="status"></div>',
                timeout: 10,
                css: {
                    backgroundColor: 'transparent',
                    border: '0'
                },
                overlayCSS: {
                    backgroundColor: '#fff',
                    opacity: 0.8
                }
            });
        }
    }
</script>

<!-- Apaga um elemento da string -->
<script>
    function remove(str, sub) {
        i = str.indexOf(sub);
        r = "";
        if (i == -1) return str;
        r += str.substring(0, i) + remove(str.substring(i + sub.length), sub);
        return r;
    }
</script>

<!-- Função para validar CPF -->
<script>
    function validarCPF() {

        var cpf = document.getElementById("cpf").value;
        var filtro = /^\d{3}.\d{3}.\d{3}-\d{2}$/i;
        if (cpf == "") {
            return true;
        }
        if (!filtro.test(cpf)) {
            alert("Por favor digite um CPF válido!");
            document.getElementById('cpf').value = '';
            return false;
        }

        cpf = remove(cpf, ".");
        cpf = remove(cpf, "-");

        if (cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" ||
            cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" ||
            cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" ||
            cpf == "88888888888" || cpf == "99999999999") {
            alert("Por favor digite um CPF válido!");
            document.getElementById('cpf').value = '';
            return false;
        }

        soma = 0;
        for (i = 0; i < 9; i++)
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        resto = 11 - (soma % 11);
        if (resto == 10 || resto == 11)
            resto = 0;
        if (resto != parseInt(cpf.charAt(9))) {
            alert("Por favor digite um CPF válido!");
            document.getElementById('cpf').value = '';
            return false;
        }
        soma = 0;
        for (i = 0; i < 10; i++)
            soma += parseInt(cpf.charAt(i)) * (11 - i);
        resto = 11 - (soma % 11);
        if (resto == 10 || resto == 11)
            resto = 0;
        if (resto != parseInt(cpf.charAt(10))) {
            alert("Por favor digite um CPF válido!");
            document.getElementById('cpf').value = '';
            return false;
        }
        return true;
    }
</script>

