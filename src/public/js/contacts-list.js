let modalInstance = null;
const modelElem = document.querySelector('#add-contact-modal');
modelElem.addEventListener('shown.bs.modal', function (){
    modalInstance = bootstrap.Modal.getInstance(modelElem);
});
modelElem.addEventListener('hidden.bs.modal', function (){
    modalInstance = null;
});

window.onkeydown = function(key) {
    if (key.key === 'Enter' && modalInstance !== null) {
        document.querySelector('#submit-add-contact').click();
    }
}

function deleteBtnRegisterOnClick () {
    document.querySelectorAll('.delete-contact').forEach(function (item) {
        item.onclick = function (item) {
            const id = item.target.getAttribute('data-id');
            const row = item.target.parentNode.parentNode;

            const xhr = getXHR('DELETE', `/${id}/delete}`);
            xhr.send();

            xhr.addEventListener('load', xhrDeleteListener.bind(xhr, row), false);
        };
    });
}
deleteBtnRegisterOnClick();

function xhrAddListener(form, evt) {
    const responseData = JSON.parse(this.responseText);
    const contact = responseData.model;
    console.log(contact);

    const tableBody = document.querySelector('#contacts-list-body');
    const firstRow = tableBody.firstChild;

    const newRow = document.createElement('tr');
    newRow.classList = 'align-middle';
    newRow.innerHTML =
        `<th class="text-center">${contact.id}</th>` +
        `<td>${contact.email_address}</td>` +
        `<td>${contact.name}</td>` +
        `<td>${contact.created_at}</td>` +
        `<td class="text-center"><i class="btn btn-danger delete-contact bi bi-trash" data-id="${contact.id}"></i></td>`;

    tableBody.insertBefore(newRow, firstRow);

    document.querySelector('#close-add-contact').click();

    deleteBtnRegisterOnClick();

    const inputs = form.querySelectorAll('input');
    inputs.forEach(function (elm) {
        elm.value = '';
    })
}

function xhrDeleteListener(row, evt) {
    if ([200,204].includes(this.status)) {
        row.remove();
    }
}

document.querySelector('#submit-add-contact').onclick = function() {
    const form = document.querySelector('#add-contact-form');

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const formData = new FormData(form);
    const dataObject = Object.fromEntries(formData.entries());

    const xhr = getXHR('POST', '/add');
    xhr.setRequestHeader( 'Content-Type', 'application/json' );
    xhr.send( JSON.stringify(dataObject) );

    xhr.addEventListener('load', xhrAddListener.bind(xhr, form));

    // todo show error message when BE returns error

    xhr.response;
};

function getXHR(method, url) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url);

    return xhr;
}
