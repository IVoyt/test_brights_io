<?php

use app\base\Collection;
use app\models\ListContact;

/** @var array|ListContact[]|Collection $contacts */

?>
<div class="row text-end mb-4">
    <div class="col-12">
        <div id="add-contact" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-contact-modal">
            Add Contact
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <table class="table table-striped  contacts-list">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">sort order</th>
                    <th class="text-center">ID</th>
                    <th>Email Address</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody id="contacts-list-body">
                <?php foreach ($contacts as $contact): ?>
                    <tr class="align-middle" data-id="<?= $contact->id ?>">
                        <th class="text-center">
                            <button class="btn btn-outline-secondary move-before">
                                <i class="bi bi-arrow-up"></i>
                            </button>
                            <button class="btn btn-outline-secondary move-after">
                                <i class="bi bi-arrow-down"></i>
                            </button>
                        </th>
                        <th class="text-center"><?= $contact->id ?></th>
                        <td><?= $contact->email_address ?></td>
                        <td><?= $contact->name ?></td>
                        <td><?= $contact->created_at ?></td>
                        <td class="text-center">
                            <i class="btn btn-danger delete-contact bi bi-trash"
                               data-id="<?= $contact->id ?>"
                               data-bs-toggle="modal"
                               data-bs-target="#delete-contact-modal"
                            ></i>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="add-contact-modal" class="modal fade" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="add-contact-form" action="/add">
                    <div class="mb-3">
                        <input type="email" name="email_address" placeholder="Enter email..." class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="name" placeholder="Enter name..." class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="close-add-contact" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="submit-add-contact" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div>
</div>

<div id="delete-contact-modal" class="modal fade" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure want to delete the contact?
            </div>
            <div class="modal-footer">
                <button type="button" id="close-delete-contact" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" id="submit-delete-contact" class="btn btn-danger" data-id="">Yes</button>
            </div>
        </div>
    </div>
</div>

<script src="/js/contacts-list.js"></script>
<script src="/js/contacts-list-sort.js"></script>
