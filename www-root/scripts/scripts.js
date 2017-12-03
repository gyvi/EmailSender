document.addEventListener("DOMContentLoaded", function() {
    var listButton      = document.querySelector('#listButton');
    var messageLogTable = document.querySelector('#messageLog tbody');
    var fromTextField   = document.querySelector('#from');

    listButton.addEventListener('click', function() {
        var xmlHttp = new XMLHttpRequest();
        var Url = "log";
        var query;

        if (fromTextField.value.trim().length > 0) {
            query = {from : fromTextField.value};
        } else {
            query = {};
        }

        xmlHttp.onReadyStatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                var result = JSON.parse(this.responseText);
                showResult(result);
            } else if (this.readyState === 4 && this.status === 400) {
                var errorResult = JSON.parse(this.responseText);

                if (errorResult.hasOwnProperty('statusMessage')) {
                    window.alert(errorResult.statusMessage);
                }
            }
        };

        xmlHttp.open("POST", Url, true);
        xmlHttp.setRequestHeader("Content-Type", "application/json");
        xmlHttp.send(JSON.stringify(query));
    });

    /**
     * Show result.
     *
     * @param result
     */
    function showResult(result) {

        if (result.status === 0) {
            clearRows();
        }

        if (result.messages.length > 0) {
            showMessages(result.messages);
        }
    }

    /**
     * Clear table rows.
     */
    function clearRows() {
        var tableRows = document.querySelectorAll('#messageLog tbody tr');

        if (tableRows.length > 0) {
            for (i = 0; i < tableRows.length; i++) {
                messageLogTable.deleteRow(0);
            }
        }
    }

    /**
     * Show messages.
     *
     * @param messages
     */
    function showMessages(messages) {
        for (i = 0; i < messages.length; i++) {
            showMessage(messages[i]);
        }
    }

    /**
     * Show a message.
     *
     * @param message
     */
    function showMessage(message) {
        var row = messageLogTable.insertRow();

        var logId     = row.insertCell(0);
        var messageId = row.insertCell(1);
        var from      = row.insertCell(2);
        var to        = row.insertCell(3);
        var subject   = row.insertCell(4);
        var logged    = row.insertCell(5);
        var queued    = row.insertCell(6);
        var sent      = row.insertCell(7);
        var delay     = row.insertCell(8);
        var error     = row.insertCell(9);

        logId.innerText     = message.messageLogId;
        messageId.innerText = message.messageId;
        from.innerText      = message.from.address;
        to.innerText        = message.recipients.to[0].address + '...';
        subject.innerText   = message.subject;
        logged.innerText    = message.logged;
        queued.innerText    = message.queued;
        sent.innerText      = message.sent;
        delay.innerText     = message.delay;
        error.innerText     = message.errorMessage;

        if (message.status === -1) {
            row.classList.add('error');
        } else if (message.status === 1) {
            row.classList.add('queued');
        } else if (message.status === 2) {
            row.classList.add('sent');
        } else {
            row.classList.add('stored');
        }
    }
});