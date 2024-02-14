document.querySelectorAll('.move-before').forEach(function (item) {
    if (!item.parentNode.hasAttribute('disabled')) {
        item.onclick = function() {
            const row = item.closest('tr');
            const previousRow = row.previousElementSibling;
            if (previousRow) {
                document.querySelector('#contacts-list-body').insertBefore(row, previousRow);

                updateMoveButtonsDisabledAttribute();

                updateSorting();
            }
        }
    }
});

document.querySelectorAll('.move-after').forEach(function (item) {
    if (!item.parentNode.hasAttribute('disabled')) {
        item.onclick = function() {
            const row = item.closest('tr');
            const nextRow = row.nextElementSibling;
            if (nextRow) {
                document.querySelector('#contacts-list-body').insertBefore(nextRow, row);

                updateMoveButtonsDisabledAttribute();

                updateSorting();
            }
        }
    }
});

function updateMoveButtonsDisabledAttribute () {
    const moveBeforeButtons = document.querySelectorAll('.move-before');
    const moveAfterButtons = document.querySelectorAll('.move-after');
    moveBeforeButtons.forEach(function(item) {
        item.disabled = false;
    });
    moveBeforeButtons[0].disabled = true;

    moveAfterButtons.forEach(function(item) {
        item.disabled = false;
    });
    moveAfterButtons[moveAfterButtons.length - 1].disabled = true;
}

updateMoveButtonsDisabledAttribute();

function updateSorting() {
    const rows = document.querySelectorAll('#contacts-list-body > tr');
    const items = {};
    rows.forEach(function(item, index) {
        items[item.getAttribute('data-id')] = index;
    });

    const xhr = getXHR('POST', '/sort');
    xhr.setRequestHeader( 'Content-Type', 'application/json' );
    xhr.send( JSON.stringify(items) );

    // xhr.addEventListener('load', xhrAddListener);
}

// function xhrSortListener() {
//     const responseData = JSON.parse(this.responseText);
//     const contact = responseData.model;
//     console.log(contact);
// }
