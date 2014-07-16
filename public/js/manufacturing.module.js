/*
 * 
 */
function populateValuesInProductSelectBoxId(url, elementForEvent, populatedElement) {
//    if (jQuery('input[name="' + elementForEvent + '"]').val() !== "")
//    {
    jQuery('select[id="' + populatedElement + '"]').html('<option value="">Please Select Product</option>');
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        success: function(data) {
            productJsonStr = data;
//                populateSelectBox(productJsonStr, populatedElement);
            jQuery.each(data, function(key, value) {
                jQuery('select[id="' + populatedElement + '"]')
                        .append(jQuery("<option></option>")
                        .attr("value", key)
                        .text(value));
            });
        }
    });
}
function checkIfNameExists(url, name, parentId) {

    $.ajax({
        type: "POST",
        url: url,
        data: {name: name, parent: parentId}
    }).success(function(data) {
        alert(data);
    });
}

function populateValuesInWarehouseSelectBox(url, elementForEvent, populatedElement) {
//    if (jQuery('input[name="' + elementForEvent + '"]').val() !== "")
//    {
    jQuery('select[name="' + populatedElement + '"]').html('<option value="">Please Select WareHouse</option>');
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        success: function(data) {
            wareHouseJsonStr = data;
//                populateSelectBox(wareHouseJsonStr, populatedElement);
            jQuery.each(data, function(key, value) {
                jQuery('select[name="' + populatedElement + '"]')
                        .append(jQuery("<option></option>")
                        .attr("value", key)
                        .text(value));
            });
        }
    });
//    }
}
function populateValuesInWarehouseSelectBoxWithId(url, elementForEvent, populatedElement) {
//    if (jQuery('input[name="' + elementForEvent + '"]').val() !== "")
//    {
    jQuery('select[id="' + populatedElement + '"]').html('<option value="">Please Select WareHouse</option>');
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        success: function(data) {
            wareHouseJsonStr = data;
//                populateSelectBox(wareHouseJsonStr, populatedElement);
            jQuery.each(data, function(key, value) {
                jQuery('select[id="' + populatedElement + '"]')
                        .append(jQuery("<option></option>")
                        .attr("value", key)
                        .text(value));
            });
        }
    });
//    }
}

function populateValuesInProductSelectBox(url, elementForEvent, populatedElement) {
//    if (jQuery('input[name="' + elementForEvent + '"]').val() !== "")
//    {
    jQuery('select[name="' + populatedElement + '"]').html('<option value="">Please Select Product</option>');
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        success: function(data) {
            productJsonStr = data;
//                populateSelectBox(productJsonStr, populatedElement);
            jQuery.each(data, function(key, value) {
                jQuery('select[name="' + populatedElement + '"]')
                        .append(jQuery("<option></option>")
                        .attr("value", key)
                        .text(value));
            });
        }
    });
    function populateValuesInProductSelectBoxId(url, elementForEvent, populatedElement) {
//    if (jQuery('input[name="' + elementForEvent + '"]').val() !== "")
//    {
        jQuery('select[id="' + populatedElement + '"]').html('<option value="">Please Select Product</option>');
        jQuery.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            success: function(data) {
                productJsonStr = data;
//                populateSelectBox(productJsonStr, populatedElement);
                jQuery.each(data, function(key, value) {
                    jQuery('select[id="' + populatedElement + '"]')
                            .append(jQuery("<option></option>")
                            .attr("value", key)
                            .text(value));
                });
            }
        });
    }

//    function populateWareHouseAndProductSelectBox(populatedElement,data) {
//        jQuery.each(data, function(key, value) {
//            jQuery('select[id="' + populatedElement + '"]')
//                    .append(jQuery("<option></option>")
//                    .attr("value", key)
//                    .text(value));
//        });
//    }
}