function validateAndSave(d) {
    var sellingObj = createObject();
    var validated = true;// validateInputs(d);
    if(validated) {
        if(sellingObj) {
            sellingJson = JSON.stringify(sellingObj);
        }

        entityVisible('sellNow','none');
        entityVisible('reset','none');
        loaderVisible('none');
        loaderVisible('block');
        //make ajax call to save
        $.ajax({
            type: "POST",
            url: "seller/post",
            data: { 'sellingObj' : sellingJson },
            beforeSend: function( xhr ) {
                entityVisible('sellNow','none');
                entityVisible('reset','none');
                loaderVisible('none');
                loaderVisible('block');
            }
        })
        .done(function(data) {
            loaderVisible('none');
            entityVisible('sellNow','block');
            entityVisible('reset','block');
            alertUser('Success! your posting was successful.');
            showPreview();
        })
        .fail(function(data) {
            highLightError();
        })
        .always(function() {
            //alert( "complete" );
        });
        //hide loader on callback
    }
}

function createObject(d) {
    var item = {};
    item.title = '131231231';//$('#title').val();
    item.description = 'asjkdakld adaklsdj alsdjklasjd';//$('#description').val();
    item.type = 1;//$('#sellingType').val();
    item.category = 1;//$('#category').val();
    item.location = 'Noida';//$('#location').val();
    item.lattitude = 28.3523232;//$('#lat').val();
    item.longitude = 37.410929;//$('#long').val();
    item.quantity = 3;//$('#quantity').val();
    item.duration = 2;//$('#duration').val();
    item.price = 10000;//$('#price').val();
    item.currency = 'INR';//$('#currency').val();
    item.negotiable = 1;//$('#negotiable').val();
    item.images = '123,213';//$('#fileIds').val();
    item.mobile = 1231232032;//$('#mobile').val();
    item.email = 'a1@spam.me';//$('#email').val();
    return item;
}

function loaderVisible(style) {
    $('#loader')[0].style.display = style;
}

function entityVisible(btnId, style) {
    $('#'+ btnId)[0].style.display = style;
}

function alertUser(msg) {
    showSuccess(msg);
    setTimeout(function () {
        hideSuccess();
    }, 5000);
}

function showSuccess(msg) {
    var alert = $('#successToast')[0];
    alert.innerText = msg;
    alert.style.opacity = 0.9;
}

function hideSuccess(){
    var alert = $('#successToast')[0];
    alert.style.opacity = 0;
}

function showPreview() {
    $('#sellNow')[0].value = 'Edit Post';
    //hide the posting layer.
    entityVisible('tips', 'none');
    entityVisible('preview', 'block');
}