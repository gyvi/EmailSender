document.addEventListener("DOMContentLoaded", function() {
    var listButton    = document.querySelector('#listButton');
    var emailLogTable = document.querySelector('#emailLog tbody');
    var fromTextField = document.querySelector('#from');

    listButton.addEventListener('click', function() {
        var xmlHttp = new XMLHttpRequest();
        var Url = "/api/v1/emails/logs";

        if (fromTextField.value.trim().length > 0) {
            Url = Url + '?from=' + fromTextField.value;
        }

        xmlHttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                console.log(this);
                var result = JSON.parse(this.responseText);
                showResult(result);
            } else if (this.readyState === 4 && this.status === 400) {
                var errorResult = JSON.parse(this.responseText);

                if (errorResult.hasOwnProperty('message')) {
                    window.alert(errorResult.message);
                }
            }
        };

        xmlHttp.open("GET", Url, true);
        xmlHttp.send();
    });

    /**
     * Show result.
     *
     * @param result
     */
    function showResult(result)
    {
        if (result.data.length > 0) {
            clearRows();
            showEmails(result.data);
        }
    }

    /**
     * Clear table rows.
     */
    function clearRows()
    {
        var tableRows = document.querySelectorAll('#emailLog tbody tr');

        if (tableRows.length > 0) {
            for (i = 0; i < tableRows.length; i++) {
                emailLogTable.deleteRow(0);
            }
        }
    }

    /**
     * Show emails.
     *
     * @param data
     */
    function showEmails(data)
    {
        for (i = 0; i < data.length; i++) {
            showEmail(data[i]);
        }
    }

    /**
     * Show a message.
     *
     * @param email
     */
    function showEmail(email)
    {
        var row = emailLogTable.insertRow();

        var emailLogId      = row.insertCell(0);
        var composedEmailId = row.insertCell(1);
        var from            = row.insertCell(2);
        var to              = row.insertCell(3);
        var subject         = row.insertCell(4);
        var logged          = row.insertCell(5);
        var queued          = row.insertCell(6);
        var sent            = row.insertCell(7);
        var delay           = row.insertCell(8);
        var error           = row.insertCell(9);

        emailLogId.innerText      = email.emailLogId;
        composedEmailId.innerText = email.composedEmailId;
        from.innerText            = email.from.address;
        to.innerText              = email.recipients.to[0].address + '...';
        subject.innerText         = email.subject;
        logged.innerText          = email.logged;
        queued.innerText          = email.queued;
        sent.innerText            = email.sent;
        delay.innerText           = email.delay;
        error.innerText           = email.errorMessage;

        if (email.status === "-1") {
            row.classList.add('error');
        } else if (email.status === "1") {
            row.classList.add('queued');
        } else if (email.status === "2") {
            row.classList.add('sent');
        } else {
            row.classList.add('stored');
        }
    }
});