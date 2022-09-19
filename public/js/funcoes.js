$(function ($) {


	// Mascaras
	$(".mask_tel").mask("(99) 99999-9999");
	$(".mask_cpf").mask("999.999.999-99");
	$(".mask_cnpj").mask("99.999.999/9999-99");
	$(".mask_data_br").mask("99/99/9999");
	$(".key_mascara").mask("*****-*****-*****-*****-*****");

	// funcao abre modal processando
	function processando(faca) {
		if (faca == "1") {
			$.blockUI({
				message: '<div class="spinner-border text-primary" role="status">Processando...</div>',
				timeout: 1000,
				css: {
					backgroundColor: 'transparent',
					border: '0'
				},
				overlayCSS: {
					backgroundColor: '#fff',
					opacity: 0.8
				}
			});
			// $('#modal_processando').modal({
			// 	show: true,
			// 	backdrop: 'static',
			// 	keyboard: false
			// });
		}
		if (faca == "0") {
			$.blockUI({
				message: '<div class="spinner-border text-primary" role="status">Processando...</div>',
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
			// $("#modal_processando").modal("hide");
		}
	}

	// Action Login
	$("#login_bt").click(function () {

		processando(1);

		var username = $("#username").val();
		var password = $("#password").val();

		$.ajax({
			type: "POST",
			url: "/sys/login",
			data: {
				'username': username,
				'password': password
			},
			success: function (msg) {

				processando(0);
				$('#status').html(msg);

			}
		});

	});



	// Mostra painel juridico ou fisico
	$("input[name=tipo_pessoa]").click(function () {
		var tipo = $("input[name=tipo_pessoa]:checked").val();
		if (tipo == "fisica") {
			$("#fisica").fadeIn(150);
			$("#juridica").fadeOut(100);

			//limpa dados de juridico
			$("#rg").val("");
			$("#cpf").val("");
			$("#data_nac").val("");
		} else {
			$("#juridica").fadeIn(150);
			$("#fisica").fadeOut(100);

			//limpa dados de fisica
			$("#cnpj").val("");
			$("#razao_social").val("");
		}
	});

	// Action cadastrar cliente
	$("#cadastrar_cliente").click(function () {

		processando(1);

		var tipo_cliente = $("#tipo_cliente").val();
		var nome = $("#nome").val();
		var tipo_pessoa = $("input[name=tipo_pessoa]:checked").val();
		var rg = $("#rg").val();
		var cpf = $("#cpf").val();
		var data_nac = $("#data_nac").val();
		var cnpj = $("#cnpj").val();
		var razao_social = $("#razao_social").val();
		var fone = $("#fone").val();
		var celular = $("#celular").val();
		var email1 = $("#email1").val();
		var email2 = $("#email2").val();
		var senha = $("#senha").val();
		var r_senha = $("#r_senha").val();
		var obs = $("#obs").val();
		var status_cli = $("#status_cli").val();
		var tipo_plano = $("#tipo_plano").val();
		var forma_pagamento = $("#forma_pagamento").val();
		var dia_vencimento = $("#dia_vencimento").val();
		var parceiro = $("input[name=parceiro]:checked").val();

		$.ajax({
			type: "POST",
			url: "/sys/cadastrar/clientes",
			data: {
				'tipo_cliente': tipo_cliente,
				'nome': nome,
				'tipo_pessoa': tipo_pessoa,
				'rg': rg,
				'cpf': cpf,
				'data_nac': data_nac,
				'cnpj': cnpj,
				'razao_social': razao_social,
				'fone': fone,
				'celular': celular,
				'email1': email1,
				'email2': email2,
				'senha': senha,
				'r_senha': r_senha,
				'obs': obs,
				'status_cli': status_cli,
				'tipo_plano': tipo_plano,
				'forma_pagamento': forma_pagamento,
				"dia_vencimento": dia_vencimento,
				'parceiro': parceiro
			},
			success: function (msg) {

				processando(0);
				$('#status').html(msg);

			}
		});

	});


	// Load cliente em licencas
	$("#clientef").change(function () {

		processando(1);
		$("#tipo_cliente").fadeIn(150);
		$("#resultado_tp_cli").html("<div class='alert alert-info alert-dismissable'>Carregando Informações...</div>");

		var id_cliente = $("#clientef").val();
		if (id_cliente != "") {
			$.ajax({
				type: "POST",
				url: "/sys/info",
				data: {
					'id_cliente': id_cliente
				},
				success: function (msg) {
					processando(0);
					$('#resultado_tp_cli').html("");
					$('#resultado_tp_cli').html(msg);

				}
			});
		} else {
			processando(0);
			$('#resultado_tp_cli').html("");
		}


	});


	// Cadastrar Licenca Action
	function getDoc(frame) {
		var doc = null;

		// IE8 cascading access check
		try {
			if (frame.contentWindow) {
				doc = frame.contentWindow.document;
			}
		} catch (err) {}

		if (doc) { // successful getting content
			return doc;
		}

		try { // simply checking may throw in ie8 under ssl or mismatched protocol
			doc = frame.contentDocument ? frame.contentDocument : frame.document;
		} catch (err) {
			// last attempt
			doc = frame.document;
		}
		return doc;
	}

	$("#enviaNovaLicenca").submit(function (e) {
		processando(1);
		var formObj = $(this);
		var formURL = "/sys/cadastrar/licenca";

		if (window.FormData !== undefined) // for HTML5 browsers
		//	if(false)
		{

			var formData = new FormData(this);
			$.ajax({
				url: formURL,
				type: 'POST',
				data: formData,
				mimeType: "multipart/form-data",
				contentType: false,
				cache: false,
				processData: false,
				success: function (data, textStatus, jqXHR) {
					processando(0);
					$("#status").html(data);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					processando(0);
					$("#status").html('');
				}
			});
			e.preventDefault();
			e.unbind();
		} else //for olden browsers
		{
			//generate a random id
			var iframeId = 'unique' + (new Date().getTime());

			//create an empty iframe
			var iframe = $('<iframe src="javascript:false;" name="' + iframeId + '" />');

			//hide it
			iframe.hide();

			//set form target to iframe
			formObj.attr('target', iframeId);

			//Add iframe to body
			iframe.appendTo('body');
			iframe.load(function (e) {
				var doc = getDoc(iframe[0]);
				var docRoot = doc.body ? doc.body : doc.documentElement;
				var data = docRoot.innerHTML;
				$("#msg").html('');
			});

		}

	});
	$("#bt_cadastrar_licenca").click(function () {
		$("#enviaNovaLicenca").submit();
	});

	// Muda status cliente action
	$(".muda_status").click(function () {

		processando(1);
		var id = $(this).attr("id");
		var valor_status = $(this).attr("vstatus");


		$.ajax({
			type: "POST",
			url: "/sys/cliente/mudastatus",
			data: {
				'id': id,
				'valor_status': valor_status
			},
			success: function (msg) {

				processando(0);
				//$('#resultado_tp_cli').html("");
				$('#status_volta').html(msg);

			}
		});

	});

	/// Acao botao licencas
	$("#action_bt_licenca").click(function () {

		//processando(1);
		var opcoes = $("#opcoes").val();
		var checked = new Array();

		$("input[name='id_licenca']:checked").each(function () {
			checked.push($(this).val());
		});

		$.ajax({
			type: "POST",
			url: "/sys/licenca/action",
			data: {
				'id_licenca': checked,
				'opcoes': opcoes
			},
			success: function (msg) {

				processando(0);
				//$('#resultado_tp_cli').html("");
				$('#status_volta').html(msg);

			}
		});


	});



	// Editar Licenca
	$("#enviaEditarLicenca").submit(function (e) {
		processando(1);
		var formObj = $(this);
		var formURL = "/sys/editar/licenca";

		if (window.FormData !== undefined) // for HTML5 browsers
		//	if(false)
		{

			var formData = new FormData(this);
			$.ajax({
				url: formURL,
				type: 'POST',
				data: formData,
				mimeType: "multipart/form-data",
				contentType: false,
				cache: false,
				processData: false,
				success: function (data, textStatus, jqXHR) {
					processando(0);
					$("#status").html(data);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					processando(0);
					$("#status").html('');
				}
			});
			e.preventDefault();
			e.unbind();
		} else //for olden browsers
		{
			//generate a random id
			var iframeId = 'unique' + (new Date().getTime());

			//create an empty iframe
			var iframe = $('<iframe src="javascript:false;" name="' + iframeId + '" />');

			//hide it
			iframe.hide();

			//set form target to iframe
			formObj.attr('target', iframeId);

			//Add iframe to body
			iframe.appendTo('body');
			iframe.load(function (e) {
				var doc = getDoc(iframe[0]);
				var docRoot = doc.body ? doc.body : doc.documentElement;
				var data = docRoot.innerHTML;
				$("#msg").html('');
			});

		}

	});
	$("#bt_editar_licenca").click(function () {
		$("#enviaEditarLicenca").submit();
	});

	/// Acao botao Financeiro
	/// bt_action_financeiro

	$("#bt_action_financeiro").click(function () {

		processando(1);
		var opcoes = $("#opcoes").val();
		var checked = new Array();

		$("input[name='codigo_fatura']:checked").each(function () {
			checked.push($(this).val());
		});

		$.ajax({
			type: "POST",
			url: "/sys/financeiro/action",
			data: {
				'id_fatura': checked,
				'opcoes': opcoes
			},
			success: function (msg) {

				processando(0);
				//$('#resultado_tp_cli').html("");
				$('#status_volta').html(msg);

			}
		});

	});

	// Acao Botao Cliente

	$("#action_bt_opcoes").click(function () {

		processando(1);
		var codigo_cli = new Array();
		$('input[name="codigo_cli"]:checked').each(function () {
			codigo_cli.push(this.value);
		});
		var opcoes = $("#opcoes").val();
		$.ajax({
			type: "POST",
			url: "/sys/clientes/action",
			data: {
				'codigo_cli': codigo_cli,
				'opcoes': opcoes
			},
			success: function (msg) {

				processando(0);
				//$('#resultado_tp_cli').html("");
				$('#status_volta').html(msg);

			}
		});

	});

	// Botao editar cliente
	// bt_editar_fatura

	$("#bt_editar_fatura").click(function () {

		processando(1);
		var id_fatura = $("#id_fatura").val();
		var status = $("#status_fatura").val();
		var data_vencimento = $("#data_vencimento").val();
		var valor = $("#valor").val();
		var descricao = $("#descricao").val();

		$.ajax({
			type: "POST",
			url: "/sys/financeiro/editar/",
			data: {
				'id_fatura': id_fatura,
				'status': status,
				'data_vencimento': data_vencimento,
				'valor': valor,
				'descricao': descricao
			},
			success: function (msg) {

				processando(0);
				//$('#resultado_tp_cli').html("");
				$('#status_volta').html(msg);

			}
		});

	});


	//Editar cliente
	// editar_cliente_sys
	$("#editarClienteSys").submit(function (e) {
		processando(1);
		var formObj = $(this);
		var formURL = "/sys/cliente/editar/";

		if (window.FormData !== undefined) // for HTML5 browsers
		//	if(false)
		{

			var formData = new FormData(this);
			$.ajax({
				url: formURL,
				type: 'POST',
				data: formData,
				mimeType: "multipart/form-data",
				contentType: false,
				cache: false,
				processData: false,
				success: function (data, textStatus, jqXHR) {
					processando(0);
					$("#status").html(data);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					processando(0);
					$("#status").html('');
				}
			});
			e.preventDefault();
			e.unbind();
		} else //for olden browsers
		{
			//generate a random id
			var iframeId = 'unique' + (new Date().getTime());

			//create an empty iframe
			var iframe = $('<iframe src="javascript:false;" name="' + iframeId + '" />');

			//hide it
			iframe.hide();

			//set form target to iframe
			formObj.attr('target', iframeId);

			//Add iframe to body
			iframe.appendTo('body');
			iframe.load(function (e) {
				var doc = getDoc(iframe[0]);
				var docRoot = doc.body ? doc.body : doc.documentElement;
				var data = docRoot.innerHTML;
				$("#msg").html('');
			});

		}

	});
	$("#editar_cliente_sys").click(function () {
		$("#editarClienteSys").submit();
	});



	///////////////////////////////////////////////////////
	/////////////////////// Cliente ///////////////////////
	///////////////////////////////////////////////////////


	// Action Login CLietne
	$("#login_bt_cliente").click(function () {

		processando(1);

		var username = $("#username").val();
		var password = $("#password").val();

		$.ajax({
			type: "POST",
			url: "/login",
			data: {
				'username': username,
				'password': password
			},
			success: function (msg) {

				processando(0);
				$('#status').html(msg);

			}
		});

	});


	// Cadastrar licenca cliente

	$("#enviaNovaLicencaCliente").submit(function (e) {
		processando(1);
		var formObj = $(this);
		var formURL = "/cadastrar/licenca";

		if (window.FormData !== undefined) // for HTML5 browsers
		//	if(false)
		{

			var formData = new FormData(this);
			$.ajax({
				url: formURL,
				type: 'POST',
				data: formData,
				mimeType: "multipart/form-data",
				contentType: false,
				cache: false,
				processData: false,
				success: function (data, textStatus, jqXHR) {
					processando(0);
					$("#status").html(data);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					processando(0);
					$("#status").html('');
				}
			});
			e.preventDefault();
			e.unbind();
		} else //for olden browsers
		{
			//generate a random id
			var iframeId = 'unique' + (new Date().getTime());

			//create an empty iframe
			var iframe = $('<iframe src="javascript:false;" name="' + iframeId + '" />');

			//hide it
			iframe.hide();

			//set form target to iframe
			formObj.attr('target', iframeId);

			//Add iframe to body
			iframe.appendTo('body');
			iframe.load(function (e) {
				var doc = getDoc(iframe[0]);
				var docRoot = doc.body ? doc.body : doc.documentElement;
				var data = docRoot.innerHTML;
				$("#msg").html('');
			});

		}

	});
	$("#bt_cadastrar_licenca_cliente").click(function () {
		$("#enviaNovaLicencaCliente").submit();
	});

	/// Acao botao licencas clientes
	$("#action_bt_licenca_cliente").click(function () {

		processando(1);

		var opcoes = $("#opcoes").val();
		var checked = new Array();

		$("input[name='id_licenca']:checked").each(function () {
			checked.push($(this).val());
		});

		$.ajax({
			type: "POST",
			url: "/licenca/actions",
			data: {
				'opcoes': opcoes,
				'idlicenca': checked
			},
			success: function (msg) {

				processando(0);
				$('#status').html(msg);

			}
		});

	});

	// Final
});