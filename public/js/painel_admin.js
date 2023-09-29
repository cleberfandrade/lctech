/*
function construirArray(qtdElementos) {
    this.length = qtdElementos
}

var arrayDia = new construirArray(7);
arrayDia[0] = "Domingo";
arrayDia[1] = "Segunda-Feira";
arrayDia[2] = "Terça-Feira";
arrayDia[3] = "Quarta-Feira";
arrayDia[4] = "Quinta-Feira";
arrayDia[5] = "Sexta-Feira";
arrayDia[6] = "Sabado";

var arrayMes = new construirArray(12);
arrayMes[0] = "Janeiro";
arrayMes[1] = "Fevereiro";
arrayMes[2] = "Março";
arrayMes[3] = "Abril";
arrayMes[4] = "Maio";
arrayMes[5] = "Junho";
arrayMes[6] = "Julho";
arrayMes[7] = "Agosto";
arrayMes[8] = "Setembro";
arrayMes[9] = "Outubro";
arrayMes[10] = "Novembro";
arrayMes[11] = "Dezembro";

function mostrarDataHora(hora, diaSemana, dia, mes, ano) {
    var retorno = "&nbsp; " + hora + " ";

    retorno += "&nbsp;&nbsp;" + diaSemana + ", " + dia + " de " + mes + " de " + ano;

    document.getElementById("datahora").innerHTML = retorno;
}
function mostrarHora(hora) {
    var retorno = "&nbsp; " + hora + " ";
    document.getElementById("hora").innerHTML = retorno;
}
function getMesExtenso(mes) {
    return this.arrayMes[mes];
}


function getDiaExtenso(dia) {
    return this.arrayDia[dia];
}
function atualizarHora() {
    dataAtual = new Date();
    dia = dataAtual.getDate();
    diaSemana = getDiaExtenso(dataAtual.getDay());
    mes = getMesExtenso(dataAtual.getMonth());
    ano = dataAtual.getFullYear();
    hora = dataAtual.getHours();
    minuto = dataAtual.getMinutes();
    segundo = dataAtual.getSeconds();
    if (hora <= 9) {
        hora = '0' + hora;
    }
    if (minuto <= 9) {
        minuto = '0' + minuto;
    }
    if (segundo <= 9) {
        segundo = '0' + segundo;
    }
    horaImprimivel = hora + ":" + minuto + ":" + segundo;
    mostrarHora(horaImprimivel);
   
}
function atualizarDataHora() {
    dataAtual = new Date();
    dia = dataAtual.getDate();
    diaSemana = getDiaExtenso(dataAtual.getDay());
    mes = getMesExtenso(dataAtual.getMonth());
    ano = dataAtual.getFullYear();
    hora = dataAtual.getHours();
    minuto = dataAtual.getMinutes();
    segundo = dataAtual.getSeconds();
    if (hora <= 9) {
        hora = '0' + hora;
    }
    if (minuto <= 9) {
        minuto = '0' + minuto;
    }
    if (segundo <= 9) {
        segundo = '0' + segundo;
    }
    horaImprimivel = hora + ":" + minuto + ":" + segundo;
    mostrarDataHora(horaImprimivel, diaSemana, dia, mes, ano);
    setTimeout("atualizarDataHora()", 1000);
    setTimeout("atualizarHora()", 1000);
}
*/

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});

$(document).on('blur', ".maiusculo", function () {
    $(this).val(function (_, val) {
        return val.toUpperCase();
    });
});
//MASCARA PARA CEP
$(document).ready(function(){
	$("#cep").mask("99.999-999");
});

//MASCARA PARA CPF/CNPJ
$("#cpfcnpj").keydown(function(){
    try {
        $("#cpfcnpj").unmask();
    } catch (e) {}

    var tamanho = $("#cpfcnpj").val().length;

    if(tamanho < 11){
        $("#cpfcnpj").mask("999.999.999-99");
    } else {
        $("#cpfcnpj").mask("99.999.999/9999-99");
    }

    // ajustando foco
    var elem = this;
    setTimeout(function(){
        // mudo a posição do seletor
        elem.selectionStart = elem.selectionEnd = 10000;
    }, 0);
    // reaplico o valor para mudar o foco
    var currentValue = $(this).val();
    $(this).val('');
    $(this).val(currentValue);
});

//MASCARA PARA TELEFONE/CELULAR <input type="tel" maxlength="15" onkeyup="handlePhone(event)" />
const handlePhone = (event) => {
    let input = event.target
    input.value = phoneMask(input.value)
  }
  
  const phoneMask = (value) => {
    if (!value) return ""
    value = value.replace(/\D/g,'')
    value = value.replace(/(\d{2})(\d)/,"($1) $2")
    value = value.replace(/(\d)(\d{4})$/,"$1-$2")
    return value
  }