function validateAndSave(d) {
    var sellingObj = createObject();
    var validated = true;// validateInputs(d);
    if(validated) {

        //make ajax call to save
        $.ajax({
            type: "POST",
            url: "seller/post",
            data: { 'data' : sellingObj },
            beforeSend: function( xhr ) {
                hideLoader();
                showLoader();
            }
        })
        .done(function() {
            hideLoader();
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
    item.fileIds = '123,213';//$('#fileIds').val();
    item.fileNames = 'sample.jpg';//$('#fileNames').val();
    item.mobile = 12312320320;//$('#mobile').val();
    item.email = 'a1@spam.me';//$('#email').val();
    item.tnc = 1;//$('#tnc').val();
}
