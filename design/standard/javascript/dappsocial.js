function limitChars(textid, limit, infodiv)

{
    var text = $('#'+textid).val(); 

    var textlength = text.length;

    if(textlength > limit)

    {

        //$('#' + infodiv).html('You cannot write more then '+limit+' characters!');
        
        $('#'+textid).val(text.substr(0,limit));
        $('#' + infodiv).html(0);
        return false;

    }

    else

    {

        //$('#' + infodiv).html('You have '+ (limit - textlength) +' characters left.');
        $('#' + infodiv).html((limit - textlength));
        return true;

    }

}

