/**
 * Funcao da qual depende as mascaras de float/percentagem.
 */
String.prototype.reverse = function(){
    return this.split('').reverse().join(''); 
};

/**
 * Mascara CNPJ.
 */
 function maskCnpj(campo, evento){
    var tecla     = (!evento) ? window.event.keyCode : evento.which;
    var valor     = campo.value.replace(/[^\d]+/gi,'').reverse();
    var resultado = "";
    var mascara   = "##.###.###/####-##".reverse();

    for(var x = 0, y = 0; x < mascara.length && y < valor.length;){
        if(mascara.charAt(x) != '#'){
            resultado += mascara.charAt(x);
            x++;
        }else{
            resultado += valor.charAt(y);
            y++;
            x++;
        }
    }

    campo.value = resultado.reverse();
}

/**
 * Mascara PIS.
 */
 function maskPis(campo, evento){
    var tecla     = (!evento) ? window.event.keyCode : evento.which;
    var valor     = campo.value.replace(/[^\d]+/gi,'').reverse();
    var resultado = "";
    var mascara   = "####.#####.##/#".reverse();

    for(var x = 0, y = 0; x < mascara.length && y < valor.length;){
        if(mascara.charAt(x) != '#'){
            resultado += mascara.charAt(x);
            x++;
        }else{
            resultado += valor.charAt(y);
            y++;
            x++;
        }
    }

    campo.value = resultado.reverse();
}

/**
 * Mascara float com duas casas decimais.
 */
 function maskFloat2(campo, evento){
    var tecla     = (!evento) ? window.event.keyCode : evento.which;
    var valor     = campo.value.replace(/[^\d]+/gi,'').reverse();
    var resultado = "";
    var mascara   = "##.###.###,##".reverse();

    for(var x = 0, y = 0; x < mascara.length && y < valor.length;){
        if(mascara.charAt(x) != '#'){
            resultado += mascara.charAt(x);
            x++;
        }else{
            resultado += valor.charAt(y);
            y++;
            x++;
        }
    }

    campo.value = resultado.reverse();
}

/**
 * Mascara float com tres casas decimais.
 */
function maskFloat3(campo, evento){
    var tecla     = (!evento) ? window.event.keyCode : evento.which;
    var valor     = campo.value.replace(/[^\d]+/gi,'').reverse();
    var resultado = "";
    var mascara   = "##.###.###,###".reverse();

    for(var x = 0, y = 0; x < mascara.length && y < valor.length;){
        if(mascara.charAt(x) != '#'){
            resultado += mascara.charAt(x);
            x++;
        }else{
            resultado += valor.charAt(y);
            y++;
            x++;
        }
    }

    campo.value = resultado.reverse();
}

/**
 * Mascara de percentagem com duas casas decimais.
 */
function maskPercent2(campo, evento){
    var tecla     = (!evento) ? window.event.keyCode : evento.which;
    var valor     = campo.value.replace(/[^\d]+/gi,'').reverse();
    var resultado = "";
    var mascara   = "###,##".reverse();

    for(var x = 0, y = 0; x < mascara.length && y < valor.length;){
        if(mascara.charAt(x) != '#'){
            resultado += mascara.charAt(x);
            x++;
        }else{
            resultado += valor.charAt(y);
            y++;
            x++;
        }
    }

    campo.value = resultado.reverse();
}
