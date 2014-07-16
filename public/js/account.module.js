/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 */
/* Accounts Form JS*/
function setAccountFormViewAccordingly()
{
    var categoryVal = parseInt(jQuery('select[name="category"]').val());
    jQuery('[name="code"]').attr("readonly", "readonly");

    jQuery('select[name="account_type"]').attr("disabled", true);
    jQuery('select[name="branch"]').attr("disabled", true);
    jQuery('select[name="parent"]').attr("disabled", true);

    jQuery('label').has('select[name="account_type"]').hide();
    jQuery('label').has('select[name="branch"]').hide();
    jQuery('label').has('select[name="parent"]').hide();

    switch (categoryVal) {
        case SUPER_CONTROL:
            break;
        case CONTROL:
            jQuery('select[name="parent"]').attr("disabled", false);
            jQuery('label').has('select[name="parent"]').show();
            break;
        case DETAILED:
            jQuery('select[name="account_type"]').attr("disabled", false);
            jQuery('select[name="branch"]').attr("disabled", false);
            jQuery('select[name="parent"]').attr("disabled", false);

            jQuery('label').has('select[name="account_type"]').show();
            jQuery('label').has('select[name="branch"]').show();
            jQuery('label').has('select[name="parent"]').show();
            break;
    }
}

function generateAccountCode(url)
{
    var categoryVal = parseInt(jQuery('select[name="category"]').val());
    var classVal = parseInt(jQuery('select[name="class"]').val());
    var parentVal = jQuery('select[name="parent"]').val();

    switch (classVal) {
        case ASSET:
            jQuery('[name="code"]').val(CODE_ASSET);
            break;
        case INCOME:
            jQuery('[name="code"]').val(CODE_INCOME);
            break;
        case EXPENSE:
            jQuery('[name="code"]').val(CODE_EXPENSE);
            break;
        case LIABILITY:
            jQuery('[name="code"]').val(CODE_LIABILITY);
            break;
        case CAPITAL:
            jQuery('[name="code"]').val(CODE_CAPITAL);
            break;
        default:
            jQuery('[name="code"]').val('');
            break;
    }
    if (categoryVal !== SUPER_CONTROL && parentVal !== "")
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

function  getVocuherNo(url, voucherType) {
    jQuery.ajax({
        type: "POST",
        url: url,
        data: {voucherType: voucherType}
    }).success(function(data) {
        jQuery('input[name="voucher_number"]').val(data);
    });
}

function populateZeroIndexAccountAndGlobalVarForSBGrid(url, elementForEvent, populatedElement) {
    if (jQuery('select[name="' + elementForEvent + '"]').val() !== "")
    {
        jQuery.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var zeroIndexaccountJsonStr = data.zeroIndexAccount;
                setGridSelectBoxAccount(data.gridSelectBoxAccount);
//                gridAccountsJsonStr = data.gridSelectBoxAccount;
                appendOptionsInSelectBox(populatedElement, zeroIndexaccountJsonStr);
                appendOptionsInSelectBox('account-1', data.gridSelectBoxAccount);
            }
        });
    }
}

function setGridSelectBoxAccount(data)
{
    gridAccountsJsonStr = data;
}

function getGridSelectBoxAccount()
{
    return gridAccountsJsonStr;
}
/////////////////////////////////Code TO populate DropDown For Name and Account Type
function appendOptionsInSelectBox(element, data) {
    jQuery('select[id="' + element + '"]').html('<option value="">Select Account</option>');
    jQuery.each(data, function(key, value) {
        jQuery('select[id="' + element + '"]')
                .append(jQuery("<option></option>")
                .attr("value", key)
                .text(value));
    });
}

//**********************
function getDebitOrCreditColForAddRow(counter)
{
    var debitOrCreditCol;
    var idDebit = 'debit-' + counter;
    var idCredit = 'credit-' + counter;
//    if (jQuery('td.colDebit').is(':visible'))
//    {
//        debitOrCreditCol = '<td class="colDebit" style="display: table-cell;"><input type="text" id="' + idDebit + '" name="debit[]' + '" /></td>';
//        debitOrCreditCol += '<td class="colCredit" style="display: none;"><input type="text" id="' + idCredit + '" name="credit[]' + '" /></td>';
//    }
//    else if (jQuery('td.colCredit').is(':visible'))
//    {
//        debitOrCreditCol = '<td class="colDebit" style="display: none;"><input type="text" id="' + idDebit + '" name="debit[]' + '" /></td>';
//        debitOrCreditCol += '<td class="colCredit" style="display: table-cell;"><input type="text" id="' + idCredit + '" name="credit[]' + '" /></td>';
//    }
    var voucherBehavior = parseInt(jQuery('[name="behavior"][checked]').attr('value'));
    switch (voucherBehavior)
    {
        case PAYMENT:
            debitOrCreditCol = '<td class="colDebit" style="display: table-cell;"><input type="text" id="' + idDebit + '" name="debit[]' + '" /></td>';
            debitOrCreditCol += '<td class="colCredit" style="display: none;"><input type="text" id="' + idCredit + '" name="credit[]' + '" /></td>';
            break;
        case RECEIPT:
            debitOrCreditCol = '<td class="colDebit" style="display: none;"><input type="text" id="' + idDebit + '" name="debit[]' + '" /></td>';
            debitOrCreditCol += '<td class="colCredit" style="display: table-cell;"><input type="text" id="' + idCredit + '" name="credit[]' + '" /></td>';
            break;
        case JOURNAL:
            debitOrCreditCol = '<td class="colDebit" style="display: table-cell;"><input type="text" id="' + idDebit + '" name="debit[]' + '" /></td>';
            debitOrCreditCol += '<td class="colCredit" style="display: table-cell;"><input type="text" id="' + idCredit + '" name="credit[]' + '" /></td>';
            break;
        case TRANSFER:
            debitOrCreditCol = '<td class="colDebit" style="display: table-cell;"><input type="text" id="' + idDebit + '" name="debit[]' + '" /></td>';
            debitOrCreditCol += '<td class="colCredit" style="display: none;"><input type="text" id="' + idCredit + '" name="credit[]' + '" /></td>';
            break;
    }
    return debitOrCreditCol;
}

////***************************
//function setDebitOrCreditValForZeroIndexElement(counter)
//{
//    var amount = 0.0;
//    if (jQuery('label.colCredit').is(':hidden'))
//    {
//        if (jQuery('#debit-0').val() === '')
//            jQuery('#debit-0').val(0)
//        amount = parseFloat(jQuery('#debit-0').val()) + parseFloat(jQuery('#credit-' + counter).val());
//        jQuery('#debit-0').val(amount);
//
//    }
//    else if (jQuery('label.colDebit').is(':hidden'))
//    {
//        if (jQuery('#credit-0').val() === '')
//            jQuery('#credit-0').val(0)
//        amount = parseFloat(jQuery('#credit-0').val()) + parseFloat(jQuery('#debit-' + counter).val());
//        jQuery('#credit-0').val(amount);
//    }
//}

function getTotalAmountOfDebitOrCredit()
{
    if (jQuery('td.colCredit').is(':visible'))
    {
        var sum = 0;
        $('input[id^="credit-"]').each(function() {
            sum += Number(jQuery(this).val());
        });
        jQuery('#debit-0').val(sum);
    }
    else if (jQuery('td.colDebit').is(':visible'))
    {
        var sum = 0;
        $('input[id^="debit-"]').each(function() {
            sum += Number(jQuery(this).val());
        });
        jQuery('#credit-0').val(sum);
    }
    jQuery('#totalAmount').val(sum);
    jQuery('#totalAmount2').val(sum);
}

//***************************
function setVoucherFormAccordingToVrType(rowNumber)
{
    var voucherType = parseInt(jQuery('select[name="voucher_type"]').val());
    var debitLegendHeading = 'Debit Account Info For Voucher Transaction';
    var crediLegendHeading = 'Credit Account Info For Voucher Transaction';
    var spanText = '';
    jQuery('label.forBank').hide();
    jQuery('.legendHeadingOfZeroIndexAccount').show();
//    jQuery('.domElement').parent().html('');
//    jQuery('.domElement').html('');
    jQuery('.domElement').remove();
    jQuery('#totalAmount').val(initialValForTotalAmount);
    jQuery(':radio[name="behavior"]').attr('checked', false);
    jQuery('tr.tblFooterTd td:eq(4)').hide();
//    jQuery('tr.tblFooterTd td:eq(5)').css('visibility', 'hidden');
    gridRowNumber = rowNumber;
    switch (voucherType)
    {
        case CASH_PAYMENT_VOUCHER:
            spanText = 'Credit Cash Account:';
            setPaymentElements(crediLegendHeading, debitLegendHeading, spanText);
            break;
        case BANK_PAYMENT_VOUCHER:
            spanText = 'Credit Bank Account:';
            setPaymentElements(crediLegendHeading, debitLegendHeading, spanText);
            //***** Specific Element For Bank
            jQuery('label.forBank').show();
            break;
        case CASH_RECEIPT_VOUCHER:
            spanText = 'Debit Cash Account:';
            setReceiptElements(crediLegendHeading, debitLegendHeading, spanText);
            break;
        case BANK_RECEIPT_VOUCHER:
            spanText = 'Debit Bank Account:';
            setReceiptElements(crediLegendHeading, debitLegendHeading);
            //***** Specific Element For Bank
//            jQuery('label.forBank').show();
            break;
        case JOURNAL_VOUCHER:
            //******* ON/Off for Zero-Index Account For Voucher-Detail
            jQuery('.legendHeadingOfZeroIndexAccount').hide();
            //****** ON/Off for cols of the Table Rows for Voucher Details
            jQuery('.legendHeadingOfOtherIndexOfAccounts').text();
            jQuery('th.colDebit').show();
            jQuery('th.colCredit').show();
            jQuery('td.colDebit').show();
            jQuery('td.colCredit').show();
            jQuery(':radio[value=' + JOURNAL + ']').attr('checked', true);
            jQuery('tr.tblFooterTd td:eq(4)').show();
            break;
        case BANK_TO_BANK_TRANSFER_VOUCHER:
            spanText = 'Credit Bank Account:';
            setPaymentElements(crediLegendHeading, debitLegendHeading, spanText);
            jQuery(':radio[value=' + TRANSFER + ']').attr('checked', true);
            jQuery('label.forBank').show();
            break;
        case CASH_TO_CASH_TRANSFER_VOUCHER:
            spanText = 'Credit Bank Account:';
            setPaymentElements(crediLegendHeading, debitLegendHeading, spanText);
            jQuery(':radio[value=' + TRANSFER + ']').attr('checked', true);
            break;
        default:
            //******* ON/Off for Zero-Index Account For Voucher-Detail
            jQuery('.legendHeadingOfZeroIndexAccount').hide();
            //****** ON/Off for cols of the Table Rows for Voucher Details
            jQuery('.legendHeadingOfOtherIndexOfAccounts').text();
            jQuery('th.colDebit').hide();
            jQuery('th.colCredit').show();
            jQuery('td.colDebit').hide();
            jQuery('td.colCredit').show();
            jQuery(':radio[value=' + JOURNAL + ']').attr('checked', true);
            break;
    }
    jQuery('fieldset table thead tr.actionRow').show();
}
/**
 * This function is sub-fucntion of setVoucherFormAccordingToVrType() which set element for payments
 * @param {string} crediLegendHeading
 * @param {string} debitLegendHeading
 * @returns {void}
 */
function setPaymentElements(crediLegendHeading, debitLegendHeading, spanText)
{
    //******* ON/Off for Zero-Index Account For Voucher-Detail
    jQuery('fieldset.legendHeadingOfZeroIndexAccount legend').text(crediLegendHeading);
    jQuery('label.colCredit').show();
    jQuery('label.colDebit input').val(0);
    jQuery('label.colDebit').hide();
    jQuery('label.zeroIndexAccount span:first').text(spanText);
    //****** ON/Off for cols of the Table Rows for Voucher Details
    jQuery('fieldset.legendHeadingOfOtherIndexOfAccounts legend').text(debitLegendHeading);
    jQuery('th.colDebit').show();
    jQuery('th.colCredit').hide();
    jQuery('td.colDebit').show();
    jQuery('td.colCredit').hide();
    jQuery(':radio[value=' + PAYMENT + ']').attr('checked', true);
}

/**
 * This function is sub-fucntion of setVoucherFormAccordingToVrType() which set element for receipt
 * @param {string} crediLegendHeading
 * @param {string} debitLegendHeading
 * @returns {void}
 */
function setReceiptElements(crediLegendHeading, debitLegendHeading, spanText)
{
    //******* ON/Off for Zero-Index Account For Voucher-Detail
    jQuery("fieldset.legendHeadingOfZeroIndexAccount legend").text(debitLegendHeading);
    jQuery('label.colCredit input').val(0);
    jQuery('label.colCredit').hide();
    jQuery('label.colDebit').show();
    jQuery('label.zeroIndexAccount span:first').text(spanText);
    //****** ON/Off for cols of the Table Rows for Voucher Details
    jQuery('fieldset.legendHeadingOfOtherIndexOfAccounts legend').text(crediLegendHeading);
    jQuery('th.colDebit').hide();
    jQuery('th.colCredit').show();
    jQuery('td.colDebit').hide();
    jQuery('td.colCredit').show();
//    jQuery(':radio[name="behavior"]').attr('disabled', false);
    jQuery(':radio[value=' + RECEIPT + ']').attr('checked', true);
}

function startRestingAfterDeleteRow()
{
    jQuery('tr[id^=tblRow-]').each(function(i) {
        jQuery('.deleteRowLink').attr('href', 'javascript:removeRow(\'tblRow-' + i + '\')');
    });
    jQuery('[id^=account-]').each(function(i) {
        jQuery(this).attr('id', 'account-' + i);
    });
    jQuery('[id^=narration-]').each(function(i) {
        jQuery(this).attr('id', 'narration-' + i);
    });
    jQuery('[id^=debit-]').each(function(i) {
        jQuery(this).attr('id', 'debit-' + i);
//        if (i !== 0)
        jQuery('#debit-' + i).change(function() {
            getTotalAmountOfDebitOrCredit();
        });
    });
    jQuery('input[id^=credit-]').each(function(i) {
        jQuery(this).attr('id', 'credit-' + i);
        jQuery('#credit-' + i).change(function() {
            getTotalAmountOfDebitOrCredit();
        });
    });

    //reseting deleteRowLinkNo
    jQuery('.deleteRowLink').each(function(i) {
        var j = i + 1;
        jQuery(this).attr('href', 'javascript:removeRow(\'tblRow-' + j + '\');startRestingAfterDeleteRow();');
    });

    getTotalAmountOfDebitOrCredit();
}

function getExchangeRate(url, currencyVal) {
    jQuery.ajax({
        url: url,
        type: 'GET',
        data: {currency: currencyVal},
        dataType: 'json',
        success: function(response) {
            jQuery('input[name="exchange_rate"]').val(response.rate);
        }
    });
}

function makeFinancialYear()
{
    var emptyStr = ' - - ';
    var start_date = jQuery('[name="start_date"]').val();
    var end_date = jQuery('[name="end_date"]').val();
    if (start_date === '')
        start_date = emptyStr;
    if (end_date === '')
        end_date = emptyStr;

    var start = start_date.split('-');
    var end = end_date.split('-');
    var financial_year = start[2] + '-' + end[2];
    jQuery('[name="name"]').val(financial_year);
}

function validateVoucherForm()
{
    var counter = 0;
    var editTableFieldError = false;
    var msg = '';
    var debitAmount = 0;
    var creditAmount = 0;
    var jvDebitAmount = 0;
    var jvCreditAmount = 0;


    var voucherDate = jQuery('[name="voucher_date"]');
    var vrDate = new Date(voucherDate.val()).getTime();
    var startDate = FY_START_DATE.getTime();
    var endDate = FY_END_DATE.getTime();
    var voucherType = jQuery('[name="voucher_type"]');
    var voucherNumber = jQuery('[name="voucher_number"]');
    var currency = jQuery('[name="currency"]');
    var exchangeRate = jQuery('[name="exchange_rate"]');
    var chequeDate = jQuery('[name="cheque_date"]');
    var chequeNumber = jQuery('[name="cheque_number"]');
    var account0Val = "";
    if (voucherDate.val() === '')
    {
        $("#accordion").accordion({active: 0});
        voucherDate.focus();
        voucherDate.addClass('fieldErrorFocus');
        myShowAlert('Please enter Voucher Date');
        return false;
    }
    else if (!((vrDate >= startDate) && (vrDate <= endDate)))
    {
        $("#accordion").accordion({active: 0});
        voucherDate.focus();
        voucherDate.addClass('fieldErrorFocus');
        myShowAlert('Please enter Voucher Date between (' + new Date(startDate).toDateString() + ')  To  (' + new Date(endDate).toDateString() + ')');
        return false;
    }
    else if (voucherType.val() === '')
    {
        $("#accordion").accordion({active: 0});
        voucherType.focus();
        voucherType.addClass('fieldErrorFocus');
        myShowAlert('Please select Voucher Type');
        return false;
    }
    else if (voucherNumber.val() === '')
    {
        $("#accordion").accordion({active: 0});
        voucherNumber.focus();
        voucherNumber.addClass('fieldErrorFocus');
        myShowAlert('Please enter Voucher Number');
        return false;
    }
    else if (currency.val() === '')
    {
        $("#accordion").accordion({active: 0});
        currency.focus();
        currency.addClass('fieldErrorFocus');
        myShowAlert('Please select Currency');
        return false;
    }
    else if (exchangeRate.val() === '')
    {
        $("#accordion").accordion({active: 0});
        exchangeRate.focus();
        exchangeRate.addClass('fieldErrorFocus');
        myShowAlert('Please enter Exchange Rate');
        return false;
    }
    else if ((parseInt(voucherType.val()) === BANK_PAYMENT_VOUCHER || parseInt(voucherType.val()) === BANK_TO_BANK_TRANSFER_VOUCHER) && chequeDate.val() === '')
    {
        $("#accordion").accordion({active: 1});
        chequeDate.focus();
        chequeDate.addClass('fieldErrorFocus');
        myShowAlert('Please enter Cheque Date');
        return false;
    }
    else if ((parseInt(voucherType.val()) === BANK_PAYMENT_VOUCHER || parseInt(voucherType.val()) === BANK_TO_BANK_TRANSFER_VOUCHER) && chequeNumber.val() === '')
    {
        $("#accordion").accordion({active: 1});
        chequeNumber.focus();
        chequeNumber.addClass('fieldErrorFocus');
        myShowAlert('Please enter Cheque Number');
        return false;
    }
    else //For Voucher Detail Table validation
    {
        jQuery('[id^="account-"]').each(function() {
            var credit = jQuery('#credit-' + counter);
            var debit = jQuery('#debit-' + counter);

            if (counter === 0)
            {
                account0Val = jQuery(this).val();
                switch (parseInt(voucherType.val()))
                {
                    case CASH_PAYMENT_VOUCHER:
                        jQuery('[id^="debit-"]').each(function() {
                            debitAmount += parseFloat(jQuery(this).val());
                        });
                        creditAmount = parseFloat(credit.val());
                        msg = 'Please select Credit Cash Account';
                        break;
                    case CASH_RECEIPT_VOUCHER:
                        jQuery('[id^="credit-"]').each(function() {
                            creditAmount += parseFloat(jQuery(this).val());
                        });
                        debitAmount = parseFloat(debit.val());
                        msg = 'Please select Dabit Cash Account';
                        break;
                    case BANK_PAYMENT_VOUCHER:
                        jQuery('[id^="debit-"]').each(function() {
                            debitAmount += parseFloat(jQuery(this).val());
                        });
                        creditAmount = parseFloat(credit.val());
                        msg = 'Please select Credit Bank Account';
                        break;
                    case BANK_RECEIPT_VOUCHER:
                        jQuery('[id^="credit-"]').each(function() {
                            creditAmount += parseFloat(jQuery(this).val());
                        });
                        debitAmount = parseFloat(debit.val());
                        msg = 'Please select Dabit Bank Account';
                        break;
                }
                if (jQuery(this).val() === '' && parseInt(voucherType.val()) !== JOURNAL_VOUCHER)
                {
                    $("#accordion").accordion({active: 1});
                    jQuery(this).focus();
                    jQuery('fieldset div div label span.custom-combobox input.custom-combobox-input').css('border', '2px solid red');
                    jQuery('fieldset div div label span.custom-combobox').css('border', '2px solid red !important');
                    myShowAlert(msg);
                    editTableFieldError = true;
                    return false;
                }

            }
            else
            {
                jvDebitAmount += parseFloat(debit.val());
                jvCreditAmount += parseFloat(credit.val());
                if (jQuery(this).val() === '')
                {
                    jQuery("#accordion").accordion({active: 2});
                    jQuery(this).focus();
                    jQuery('fieldset table tbody tr td span input.custom-combobox-input').css('border', '2px solid red');
                    jQuery('fieldset table tbody tr td span ').css('border', '2px solid red !important');
                    myShowAlert('Please select Account at row# ' + counter);
                    editTableFieldError = true;
                    return false;
                }
                else if ((debit.val() === '' || parseInt(debit.val()) === 0) && (parseInt(voucherType.val()) === CASH_PAYMENT_VOUCHER || parseInt(voucherType.val()) === BANK_PAYMENT_VOUCHER))
                {
                    jQuery("#accordion").accordion({active: 2});
                    debit.focus();
                    debit.addClass('fieldErrorFocus');
                    myShowAlert('Please enter Deibt Amount at row# ' + counter);
                    editTableFieldError = true;
                    return false;
                }
                else if ((credit.val() === '' || parseInt(credit.val()) === 0) && (parseInt(voucherType.val()) === CASH_RECEIPT_VOUCHER || parseInt(voucherType.val()) === BANK_RECEIPT_VOUCHER))
                {
                    jQuery("#accordion").accordion({active: 2});
                    credit.focus();
                    credit.addClass('fieldErrorFocus');
                    myShowAlert('Please enter Credit Amount at row# ' + counter);
                    editTableFieldError = true;
                    return false;
                }
                else if ((parseInt(voucherType.val()) === JOURNAL_VOUCHER))
                {
                    if ((debit.val() === '' || debit.val() === 0) && (credit.val() === '' || credit.val() === 0))
                    {
                        jQuery("#accordion").accordion({active: 2});
                        debit.focus();
                        debit.addClass('fieldErrorFocus');
                        credit.addClass('fieldErrorFocus');
                        myShowAlert('Please enter Debit or Credit Amount at row# ' + counter + '. Because both can not be empty or zero');
                        editTableFieldError = true;
                        return false;
                    }
                    if (debit.val() === credit.val())
                    {
                        jQuery("#accordion").accordion({active: 2});
                        debit.focus();
                        debit.addClass('fieldErrorFocus');
                        credit.addClass('fieldErrorFocus');
                        myShowAlert('Debit and Credit Amount are provided on same account at row# ' + counter);
                        editTableFieldError = true;
                        return false;
                    }
                }
                else if ((parseInt(voucherType.val()) !== JOURNAL_VOUCHER) && account0Val === jQuery(this).val())
                {
                    jQuery("#accordion").accordion({active: 2});
                    debit.focus();
                    debit.addClass('fieldErrorFocus');
                    credit.addClass('fieldErrorFocus');
                    myShowAlert('Debit Account and Credit Account are same at row# ' + counter);
                    editTableFieldError = true;
                    return false;
                }
            }
            counter++;
        });
        if (editTableFieldError === true)
        {
            return false;
        }
    }
    if (parseInt(voucherType.val()) === JOURNAL_VOUCHER)
    {
        if (jvDebitAmount !== jvCreditAmount)
        {
            $("#accordion").accordion({active: 2});
            myShowAlert('Debit and Credit Total Amounts are not equal# ' + counter + '. Debit Total Amont is:' + jvDebitAmount + ' and Credit Total Amont is:' + jvCreditAmount);
            return false;
        }
    }
    else
    {
        if (debitAmount !== creditAmount)
        {
            $("#accordion").accordion({active: 2});
            myShowAlert('Debit and Credit Total Amounts are not equal# ' + counter + '. Debit Total Amont is:' + debitAmount + ' and Credit Total Amont is:' + creditAmount);
            return false;
        }
    }
    return true;
}