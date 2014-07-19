
/*=========== Start CLEAR FORM plugin ==============
 * usage@       $('form').clearForm()
 * usage@       $(':input').clearForm()
 * ===========================================
 * */
jQuery.fn.clearForm = function() {
    return this.each(function() {
        var type = this.type, tag = this.tagName.toLowerCase();
        if (tag == 'form')
            return jQuery(':input', this).clearForm();
        if (type == 'text' || type == 'password' || tag == 'textarea')
            this.value = '';
        else if (type == 'checkbox' || type == 'radio')
            this.checked = false;
        else if (tag == 'select')
            this.selectedIndex = -1;
    });
};
/*=========== End CLEAR FORM plugin ==============*/

/* ===================Start Code Block ============*/
//Ajax start function
jQuery(document).ajaxStart(function() {
    jQuery('#loadingImgDiv').show();
}).ajaxStop(function() {
    jQuery('#loadingImgDiv').hide();
});//Ajax end function
/* ===================End Code Block ============*/

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//        //***** For DatePicker
//        jQuery(function() {
//            jQuery(document).on("click", 'input[name="voucher_date"]', function() {
//                jQuery(this).datepicker({
//                    dateFormat: "dd-mm-yy",
//                    changeMonth: true,
//                    changeYear: true,
//                    onClose: function() {
//                        jQuery(this).valid();
//                    }
//                });
//            });
//        });
//        
//*****DOT REMOVE
function loadPage(url) {

    jQuery.ajax({
        url: url,
        type: 'get',
//        dataType: 'json',
        success: function(data) {
            jQuery('#containerDiv').html(data);
            window.history.pushState("object or string", "", url);
        },
    });
}
//*******End DOT REMOVE
//function loadPage(url) {
//
//    window.location.href = url;
//}
function loadPageInNewWindow(url)
{
//    window.location.href = url;
    window.open(url, '_blank', 'location=yes,height=870px,width=900px,scrollbars=yes,status=yes');
}

function logoutUser(url)
{
    window.location.href = url;
}

function refreshPage(url) {
//    window.location.href = url;
//    $('form').clearForm();
//****************
    jQuery.ajax({
        url: url,
        type: 'get',
//        dataType: 'json',
        success: function(data) {
            jQuery('#containerDiv').html(data);
            window.history.pushState("object or string", "", url);
//            $('form').clearForm();
        }
    });
    $('form').clearForm();
}

function postSubmitData(url, formId)
{
    var formData = $('form#' + formId).serializeArray();
    formData.push({
        name: 'submit',
        value: submitBtnsVals.value
    });
    jQuery.ajax({
        url: url,
        type: 'post',
        data: formData,
        success: function(data) {
            jQuery('#containerDiv').html(data);
            window.history.pushState("object or string", "", url);
        }
    });
    $('form').clearForm();
}

function postSubmitDataAndLoadReportViewer(url, formId, urlReportViewer)
{
    var formData = $('form#' + formId).serializeArray();
    formData.push({
        name: 'submit',
        value: submitBtnsVals.value
    });
    jQuery.ajax({
        url: url,
        type: 'post',
        data: formData,
        success: function(data) {
            loadPageInNewWindow(urlReportViewer);
        }
    });
}

function deleteRecord(url)
{
    var result = confirm("Are you sure that you want to delete the record?");
    if (result === true)
    {
        jQuery.ajax({
            url: url,
            type: 'get',
            success: function(data) {
                var isErrorExist = data.indexOf('ErrorMessage:');
                jQuery('#containerDiv').html(data);
                if (isErrorExist === -1)
                {

                    jQuery('#msgTextDiv').fadeIn(500).html(msgText.deleteSuccessMsg);
                }
                else
                {
                    jQuery('#msgTextDiv').fadeIn(500).html(msgText.deleteErrorMsg);
                }

            }
        });
    }
}

function hideMsgText()
{
//    jQuery('#msgTextDiv').hide(2000);
    jQuery('#msgTextDiv').fadeOut(5000);
}
/**
 * Add a new Row in Editable Tables and table should have editable class. Run it on click event
 *
 * @param array cols
 * 
 * @return boolean
 */
function addNewRow(cols)
{
    var counter = 0
    var newRow = '';
    if (cols instanceof Array)
    {
        if ((gridRowNumber % 2) === 0)
            newRow = '<tr id="tblRow-' + gridRowNumber + '" class="domElement evenRow">';
        else
            newRow = '<tr id="tblRow-' + gridRowNumber + '" class="domElement oddRow">';

        for (var key in cols)
        {
            counter++;
            newRow = newRow + cols[key];
        }
        newRow = newRow + '</tr>';
        jQuery('table.editable').append(newRow);
        return true;
    }
    return false;
}

/**
 * Remove a Row in Editable Tables and table should have editable class. Run it on click event
 *
 * @param array cols
 * 
 * @return boolean
 */
function removeRow(id)
{
    var rowTotalCount = 0;
    var rowNo = 0;
    jQuery('tr[id^=tblRow-]').each(function() {
        rowTotalCount++;
    });
    if (rowTotalCount > 1)
    {
        var removeDomElementClass = false;
        if (jQuery('#' + id).hasClass('domElement'))
            jQuery('#' + id).remove();
        else
        {
            jQuery('#' + id).remove();
            removeDomElementClass = true;
        }
        gridRowNumber = rowTotalCount - 1; //reseting global Row Number
        //reseting input fields id numbers
        jQuery('tr[id^=tblRow-]').each(function() {
            rowNo++;
            var idVal = 'tblRow-' + rowNo;

            if (rowNo > 0 && removeDomElementClass === true)
            {
                jQuery(this).removeClass('domElement');
                removeDomElementClass = false;
            }
            jQuery(this).removeClass('evenRow');
            jQuery(this).removeClass('oddRow');
            if ((rowNo % 2) === 0)
                jQuery(this).addClass('evenRow');
            else
                jQuery(this).addClass('oddRow');

            jQuery(this).attr('id', idVal);
            jQuery(this).attr('id', idVal);
            jQuery(this).find('td:first-child').html(rowNo); //reseting serial-No

        });
    }
    else
        alert('Atleast one row should be availabe for grid data.');
}

/**
 * populated child-select-box option from database via ajax call. Run it on click event
 *
 * @param string url
 * @param string elementForEvent
 * @param string populatedElement
 * 
 * @return nothing
 */
function populateSelectBox(url, elementForEvent, populatedElement) {
    if (jQuery('select[name="' + elementForEvent + '"]').val() !== "")
    {
        jQuery('select[name="' + populatedElement + '"]').html('<option value="">' + SelectBoxNullValue + '</option>');
        jQuery.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            success: function(data) {
                jQuery.each(data, function(key, value) {
                    jQuery('select[name="' + populatedElement + '"]')
                            .append(jQuery("<option></option>")
                            .attr("value", key)
                            .text(value));
                });
            },
        });
    }
}

function populateMaxAccountSerialId(url, Account, code) {

    if (jQuery('select[name="' + Account + '"]').val() !== "")
    {
        jQuery('select[name="' + Account + '"]').html('<option value="">Select Account</option>');
        jQuery.ajax({
            url: url,
            type: 'get',
//            dataType: 'json',
            success: function(data) {
                jQuery.each(data, function() {
                    jQuery('input[name="' + code + '"]').val(data);
                });
            },
        });
    }
}

function populateWareHouseAndProductSelectBox(populatedElement, data, text) {
    jQuery('select[id="' + populatedElement + '"]').html('<option value="">Please Select ' + text + '</option>');
    jQuery.each(data, function(key, value) {
        jQuery('select[id="' + populatedElement + '"]')
                .append(jQuery("<option></option>")
                .attr("value", key)
                .text(value));
    });
}

function populateProductSelectBox(url, elementForEvent, populatedElement) {
    if (jQuery('select[id="' + elementForEvent + '"]').val() !== "")
    {
        jQuery('select[id="' + populatedElement + '"]').html('<option value="">' + SelectBoxNullValue + '</option>');
        jQuery.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            success: function(data) {
                jQuery.each(data, function(key, value) {
                    jQuery('select[id="' + populatedElement + '"]')
                            .append(jQuery("<option></option>")
                            .attr("value", key)
                            .text(value));
                });
            },
        });
    }
    else
        alert(errorMsg);
}

function getOnlyIntegerValue(event)
{
    //******************** Only For Integer
    // Allow: backspace, delete, tab and escape
    if (event.keyCode === 46 ||
            event.keyCode === 8 ||
            event.keyCode === 9 ||
            event.keyCode === 27 ||
            (event.keyCode === 65 && event.ctrlKey === true) || // Allow: Ctrl+A
            (event.keyCode >= 35 && event.keyCode <= 39)// Allow: home, end, left, right
            )
    {
        // let it happen, don't do anything                                  
        return;
    }
    else if ((event.keyCode > 48 && event.shiftKey === true) || (event.keyCode < 58 && event.shiftKey === true)) // Deny for special characters
        event.preventDefault();
    else
    {
        // Ensure that it is a number and stop the keypress
        if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
            event.preventDefault();
        }
    }
}

function getOnlyDecimalValue(event, value)
{
    //****Start Prevention Dot/Point more than one.
    if (value.indexOf('.') >= 0)
    {
        if (event.keyCode === 110 || // Allow Dot/Point for Decimal
                event.keyCode === 190 // Allow Dot/Point for Decimal
                )
        {
            event.preventDefault();
        }
    }
    //****End Prevention Dot/Point more than one.

    if (event.keyCode === 46 ||
            event.keyCode === 8 ||
            event.keyCode === 9 ||
            event.keyCode === 27 ||
            event.keyCode === 110 || // Allow Dot/Point for Decimal
            event.keyCode === 190 || // Allow Dot/Point for Decimal
            (event.keyCode === 65 && event.ctrlKey === true) || // Allow: Ctrl+A
            (event.keyCode >= 35 && event.keyCode <= 39)// Allow: home, end, left, right
            )
    {
        // let it happen, don't do anything                                  
        return;

    }
    else if ((event.keyCode > 48 && event.shiftKey === true) || (event.keyCode < 58 && event.shiftKey === true)) // Deny for special characters
        event.preventDefault();
    else
    {
        // Ensure that it is a number and stop the keypress
        if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
            event.preventDefault();
        }
    }
}

function getOnlyAlphaValue(event)
{
    //******************** Only For Alphabetic
    // Allow: backspace, delete, tab and escape
    if (event.keyCode === 46 ||
            event.keyCode === 8 ||
            event.keyCode === 9 ||
            event.keyCode === 27 ||
            event.keyCode === 32 ||
            (event.keyCode === 65 && event.ctrlKey === true) || // Allow: Ctrl+A
            (event.keyCode >= 35 && event.keyCode <= 39)// Allow: home, end, left, right
            )
    {
        // let it happen, don't do anything                                  
        return;
    }
    else if ((event.keyCode === 54) && (event.keyCode === 16))// special check for ^
        event.preventDefault();
    else {
        // Ensure that it is an alphabet and stop the keypress
        if ((event.keyCode < 64 || event.keyCode > 91)) {
            event.preventDefault();
        }
    }
}

function getOnlyAlphaWithSlashesValue(event)
{
    //******************** Only For Alphabetic with /   
    // Allow: backspace, delete, tab and escape
    if (event.keyCode === 46 ||
            event.keyCode === 8 ||
            event.keyCode === 9 ||
            event.keyCode === 27 ||
            event.keyCode === 32 ||
            event.keyCode === 191 || //Allow Slash
            (event.keyCode === 65 && event.ctrlKey === true) || // Allow: Ctrl+A
            (event.keyCode >= 35 && event.keyCode <= 39)// Allow: home, end, left, right
            )
    {
        // let it happen, don't do anything                                  
        return;
    }
    else if ((event.keyCode === 54) && (event.keyCode === 16)) // special check for ^
        event.preventDefault();
    else
    {
        // Ensure that it is an alphabet and stop the keypress
        if ((event.keyCode < 64 || event.keyCode > 91)) {
            event.preventDefault();
        }
    }
}

//****************************Resource Code By Waqas
function generateResourceCode(url)
{
    var parentVal = jQuery('select[name="parent"]').val();
    if (parentVal !== "")
    {
        jQuery.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            success: function(data) {
                jQuery('[name="code"]').val(data);
            },
        });
    }
}
//***************************END
//****************************Resource Code By Waqas
function populateCheckBoxesForRoles(url, id)
{
    if (id !== "")
    {
        jQuery.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {roleId: id},
            success: function(data) {
                jQuery('tr').each(function() {
                    id = this.id;
                    $.each(data, function(key, value) {
                        if (id == value) {
//                            jQuery(this).find('td:first input').attr('checked', 'checked');
                            jQuery('tr#' + value + ' input[type=checkbox]').attr('checked', true);
//                            jQuery(this).attr('checked', 'checked');
                        }
                    });
                });
            },
        });
    }
}
//***************************END
//****************************Resource Code By Waqas
function checkIfRoleAlreadyExists(url, id, redirectUrl)
{
    if (id !== "")
    {
        jQuery.ajax({
            url: url,
            type: 'post',
//            dataType: 'json',
            data: {roleId: id},
            success: function(data) {
                var count = data;
                if (count == 1) {
                    alert('You Cannot Allocate Resources For This Role');
                    jQuery('select[name="role"]').find('option:first-child').prop('selected', true);
                    loadPage(redirectUrl);
                }
            },
        });
    }
}
//***************************END
//****************************Resource TD By Waqas
function populateResourceTdForRoles(url, id)
{
    if (id !== "")
    {
        jQuery.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: {roleId: id},
            success: function(data) {
                jQuery('tr').each(function() {
                    id = jQuery(this).attr('data-tt-id');
                    $.each(data, function(key, value) {
                        if (id == value) {
                            jQuery("tr#" + value).find("td.identifier").append('Allowed');
//                            jQuery("tr#" + value).append('<td><span class="identifier" style="padding-left: 38px;"><span>Allowed</td>');
                        }
                    });
                });
            },
        });
    }
}
//***************************END
function myShowAlert(msg)
{
    jQuery('#alertBoxDiv div h1').html(msg);
    jQuery('#alertBoxDiv').show();
}
function myCloseAlert()
{
    jQuery('#alertBoxDiv').hide();
}
function isScrollBarAtEnd(docContext,winContext){
//    var docHeight = $(document).height();
//    var scrollAndWinHieght = $(window).scrollTop() + $(window).height();
//******************************************************    
    var docHeight = $(docContext).height();
    var scrollPlusWinHeight = $(winContext).scrollTop() + $(winContext).height();
    if(docHeight === scrollPlusWinHeight){
        return true;
    }else{
        return false;
    }
}