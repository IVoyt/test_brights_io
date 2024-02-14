let addModalInstance = null;
let deleteModalInstance = null;
const addModelElem = document.querySelector('#add-contact-modal');
const deleteModelElem = document.querySelector('#delete-contact-modal');
addModelElem.addEventListener('shown.bs.modal', function (){
    addModalInstance = bootstrap.Modal.getInstance(addModelElem);
});
addModelElem.addEventListener('hidden.bs.modal', function (){
    addModalInstance = null;
});

deleteModelElem.addEventListener('hidden.bs.modal', function (){
    deleteModalInstance = bootstrap.Modal.getInstance(deleteModelElem);
    document.querySelector('#submit-delete-contact').setAttribute('data-id', '');
});
deleteModelElem.addEventListener('hidden.bs.modal', function (){
    deleteModalInstance = null;
});

window.onkeydown = function(key) {
    if (key.key === 'Enter' && addModalInstance !== null) {
        document.querySelector('#submit-add-contact').click();
    }
}

function deleteBtnRegisterOnClick () {
    document.querySelectorAll('.delete-contact').forEach(function(item) {
        item.onclick = function() {
            console.log(item.getAttribute('data-id'))
            document
                .querySelector('#submit-delete-contact')
                .setAttribute('data-id', item.getAttribute('data-id'));
        }
    })
}
deleteBtnRegisterOnClick();

document.querySelector('#submit-delete-contact').onclick = function (item) {
    const id = item.target.getAttribute('data-id');
    const row = document.querySelector(`#contacts-list-body > tr[data-id="${id}"]`);

    const xhr = getXHR('DELETE', `/${id}/delete}`);
    xhr.send();

    xhr.addEventListener('load', xhrDeleteListener.bind(xhr, row), false);
};

function xhrAddListener(form, evt) {
    const responseData = JSON.parse(this.responseText);
    const contact = responseData.model;
    console.log(contact);

    const tableBody = document.querySelector('#contacts-list-body');
    const firstRow = tableBody.firstChild;

    const newRow = document.createElement('tr');
    newRow.classList = 'align-middle';
    newRow.innerHTML =
        `<th class="text-center">` +
        `   <button class="btn btn-outline-secondary move-before">` +
        `       <i class="bi bi-arrow-up"></i>` +
        `   </button>` +
        `   <button class="btn btn-outline-secondary move-after">` +
        `      <i class="bi bi-arrow-down"></i>` +
        `   </button>` +
        `</th>` +
        `<th class="text-center">${contact.id}</th>` +
        `<td>${contact.email_address}</td>` +
        `<td>${contact.name}</td>` +
        `<td>${contact.created_at}</td>` +
        `<td class="text-center"><i class="btn btn-danger delete-contact bi bi-trash" data-id="${contact.id}"></i></td>`;

    tableBody.insertBefore(newRow, firstRow);

    document.querySelector('#close-add-contact').click();

    deleteBtnRegisterOnClick();

    updateMoveButtonsDisabledAttribute();

    const inputs = form.querySelectorAll('input');
    inputs.forEach(function (elm) {
        elm.value = '';
    })
}

function xhrDeleteListener(row, evt) {
    if ([200,204].includes(this.status)) {
        row.remove();
    }

    document.querySelector('#close-delete-contact').click();
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
