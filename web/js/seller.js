function validateAndSave(d) {
    var sellingObj = createObject();
    var validated = true;// validateInputs(d);
    if(validated) {
        if(sellingObj) {
            sellingJson = JSON.stringify(sellingObj);
        }

        buttonsVisible('sellNow','none');
        buttonsVisible('reset','none');
        loaderVisible('none');
        loaderVisible('block');
        //make ajax call to save
        $.ajax({
            type: "POST",
            url: "seller/post",
            data: { 'sellingObj' : sellingJson },
            beforeSend: function( xhr ) {
                buttonsVisible('sellNow','none');
                buttonsVisible('reset','none');
                loaderVisible('none');
                loaderVisible('block');
            }
        })
        .done(function() {
            loaderVisible('none');
            buttonsVisible('sellNow','show');
            buttonsVisible('reset','show');
            showSuccess();
            showPreview();
        })
        .fail(function() {
            highLightError();
        })
        .always(function() {
            alert( "complete" );
        });
        //hide loader on callback
    }
}

function createObject(d) {
    var item = {};
    item.title = '131231231';//$('#title').val();
    item.description = 'asjkdakld adaklsdj alsdjklasjd';//$('#description').val();
    item.sellingType = 'rent';//$('#sellingType').val();
    item.category = 1;//$('#category').val();
    item.location = 'Noida';//$('#location').val();
    item.lat = 28.3523232;//$('#lat').val();
    item.long = 37.410929;//$('#long').val();
    item.quantity = 3;//$('#quantity').val();
    item.duration = 2;//$('#duration').val();
    item.price = 10000;//$('#price').val();
    item.currency = 'INR';//$('#currency').val();
    item.negotiable = 1;//$('#negotiable').val();
    item.fileIds = '123,213';//$('#fileIds').val();
    item.fileNames = 'sample.jpg';//$('#fileNames').val();
    item.mobile = 1231232032;//$('#mobile').val();
    item.email = 'a1@spam.me';//$('#email').val();
    item.tnc = 1;//$('#tnc').val();
    return item;
}

function loaderVisible(style) {
    $('#loader')[0].style.display = style;
}

function buttonsVisible(btnId, style) {
    $('#'+ btnId)[0].style.display = style;
}
