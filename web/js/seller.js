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
            showPreview(data);
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
    item.title = 'PC with mouse and keyboard with table';//$('#title').val();
    item.description = 'asjkdakld adaklsdj alsdjklasjd asjkdakld adaklsdj alsdjklasjd asjkdakld adaklsdj alsdjklasjdasjkdakld adaklsdj alsdjklasjd';//$('#description').val();
    item.type = 'rent';//$('#sellingType').val();
    item.category = 1;//$('#category').val();
    item.location = 'Sec 132, Noida';//$('#location').val();
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

function showPreview(data) {
    $('#sellNow')[0].value = 'Edit Post';
    $('#postHead')[0].innerHTML = 'Edit your post';
    //hide the posting layer.
    entityVisible('tips', 'none');
    entityVisible('preview', 'block');
    createPreview(data);
    $('#editPost')[0].style.display='block';
    $('#successScroll')[0].click();
}

function createPreview(data) {
    //p means preview
    var item = JSON.parse(JSON.parse(data).item);
    $('#ptitle')[0].innerHTML = item.title;
    if(item.location)
      $('#plocation')[0].innerHTML = item.location;
    $('#pcategory')[0].innerHTML = 'PC / accessories';
    if(item.quantity)
      $('#pquantity')[0].innerHTML = 'Items : ' + item.quantity;
    if(item.price)
      $('#pprice')[0].innerHTML = ' Total Price ' + item.price + ' ' + item.currency;
    if(item.negotiable) {
      $('#pprice')[0].innerHTML += ' (negotiable)';
    }
    $('#pdescription')[0].innerHTML = item.description;
    if(item.type == 'rent' || item.type == 'share')
      $('#pavailable')[0].innerHTML = 'Available for ' + item.type + ' for ' + item.duration + ' months';

}
function resetPostingForm() {
    $('#sellingFormSection').find('input').val('');
    $('#preview')[0].style.display='none';
    $('#tips')[0].style.display='block';
    $('#sellNow')[0].value = 'Post Now';
    $('#postHead')[0].innerHTML = 'Post a Free Ad';
}
