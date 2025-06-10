// API
function getAllClients() {
    return $.ajax({
      url: 'http://localhost:8000/controller/ClientController.php/clients',
      method: 'GET',
      dataType: 'json'
    }).fail(function (jqXHR) {
      console.error('Erro ao buscar clientes:', jqXHR.statusText);
    });
}

function getOneClientById(id) {
return $.ajax({
    url: `http://localhost:8000/controller/ClientController.php/client/${id}`,
    method: 'GET',
    dataType: 'json'
}).fail(function (jqXHR) {
    console.error(`Erro ao buscar cliente ${id}:`, jqXHR.statusText);
});
}

function postClient(data) {
return $.ajax({
    url: 'http://localhost:8000/controller/ClientController.php/client',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify(data),
    dataType: 'json'
}).done(function (result) {
    console.log('Cliente adicionado com sucesso:', result);
}).fail(function (jqXHR) {
    console.error('Erro no POST:', jqXHR.statusText);
});
}

function putClient(id, data) {
    return $.ajax({
        url: `http://localhost:8000/controller/ClientController.php/client/${id}`,
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify(data),
        dataType: 'json'
    }).done(function (result) {
        console.log(`Cliente ${id} atualizado com sucesso:`, result);
    }).fail(function (jqXHR) {
        console.error(`Erro no PUT do cliente ${id}:`, jqXHR.statusText);
    });
}

function deleteClient(id) {
    return $.ajax({
        url: `http://localhost:8000/controller/ClientController.php/client/${id}`,
        method: 'DELETE',
        dataType: 'json'
    }).done(function (result) {
        console.log(`Cliente ${id} deletado com sucesso:`, result);
    }).fail(function (jqXHR) {
        console.error(`Erro ao deletar cliente ${id}:`, jqXHR.statusText);
    });
}

// Table
function renderClientsTable(clients) {
    const $tbody = $('#clientsTableBody');
    $tbody.empty();

    $.each(clients, function (_, client) {
        const $tr = $('<tr>').attr('data-id', client.id);

        $('<td>').text(client.id).appendTo($tr);
        $('<td>').text(client.name).appendTo($tr);
        $('<td>').text(client.cpf_cnpj).appendTo($tr);
        $('<td>').text(client.email || '').appendTo($tr);
        $('<td>').text(client.phone || '').appendTo($tr);

        const $tdAcoes = $('<td>').addClass('btn-container');
        $('<button>').addClass('btn btn-sm btn-primary btn-edit').text('Editar').appendTo($tdAcoes);
        $('<button>').addClass('btn btn-sm btn-danger btn-delete').text('Excluir').appendTo($tdAcoes);
        $tr.append($tdAcoes);

        $tbody.append($tr);
    });
}

// Modals
let clientModal;
let idToDelete = null;
let rowToDelete = null;

$(document).ready(function () {
clientModal = new bootstrap.Modal($('#clientModal')[0]);

getAllClients().done(function (clients) {
    if (Array.isArray(clients)) {
        renderClientsTable(clients);
    } else {
        console.error('Resposta inválida:', clients);
    }
});

$('#btnAddClient').on('click', function () {
    $('#clientModalLabel').text('Adicionar Cliente');
    $('#clientForm')[0].reset();
    $('#clientId').val('');
    clientModal.show();
});

$('#clientsTableBody').on('click', '.btn-edit', function () {
    const $row = $(this).closest('tr');
    const id = $row.data('id');
    const name = $row.find('td:eq(1)').text();
    const cpfCnpj = $row.find('td:eq(2)').text();
    const email = $row.find('td:eq(3)').text();
    const phone = $row.find('td:eq(4)').text();

    $('#clientModalLabel').text('Editar Cliente');
    $('#clientId').val(id);
    $('#clientName').val(name);
    $('#clientCpfCnpj').val(cpfCnpj);
    $('#clientEmail').val(email);
    $('#clientPhone').val(phone);

    clientModal.show();
});

$('#clientsTableBody').on('click', '.btn-delete', function () {
    const $row = $(this).closest('tr');
    idToDelete = $row.data('id');
    rowToDelete = $row;
    $('#customConfirmModal').css('display', 'flex');
});

$('#confirmYes').on('click', function () {
    deleteClient(idToDelete).done(function () {
        rowToDelete.remove();
        showNotification('Cliente excluído com sucesso!');
    }).fail(function () {
        showNotification('Erro ao excluir cliente.');
    }).always(function () {
        $('#customConfirmModal').css('display', 'none');
    });
});

$('#confirmNo').on('click', function () {
    $('#customConfirmModal').css('display', 'none');
});

$('#clientForm').on('submit', function (e) {
    e.preventDefault();

    const clientData = {
        name: $('#clientName').val(),
        cpfCnpj: $('#clientCpfCnpj').val(),
        email: $('#clientEmail').val(),
        phone: $('#clientPhone').val()
    };

    const clientId = $('#clientId').val();

    let request = clientId ? putClient(clientId, clientData) : postClient(clientData);

    request.done(function () {
        showNotification(clientId ? 'Cliente editado com sucesso!' : 'Cliente registrado com sucesso!');
        clientModal.hide();
        getAllClients().done(function (clients) {
            if (Array.isArray(clients)) {
                renderClientsTable(clients);
            }
        });
    }).fail(function () {
        showNotification('Erro ao salvar cliente.');
    });
});
});

function showNotification(message) {
    const $notification = $('#customNotification');
    $notification.text(message).fadeIn();

    setTimeout(function () {
        $notification.fadeOut();
    }, 3000);
}

// Phone Mask
$('#clientPhone').on('input', function () {
    let value = $(this).val().replace(/\D/g, '');

    if (value.length > 11) {
        value = value.slice(0, 11);
    }

    if (value.length > 6) {
        value = value.replace(/^(\d{2})(\d{4})(\d{0,5}).*/, '($1) $2-$3');
    } else if (value.length > 2) {
        value = value.replace(/^(\d{2})(\d{0,4})/, '($1) $2');
    } else if (value.length > 0) {
        value = value.replace(/^(\d{0,2})/, '($1');
    }

    $(this).val(value);
});

// CPF/CNPJ Mask
$('#clientCpfCnpj').on('input', function () {
    let value = $(this).val().replace(/\D/g, '');

    if (value.length <= 11) {
        value = value.replace(/^(\d{3})(\d)/, '$1.$2');
        value = value.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
        value = value.replace(/\.(\d{3})\.(\d{3})(\d)/, '.$1.$2-$3');
        value = value.slice(0, 14);
    } else {
        value = value.replace(/^(\d{2})(\d)/, '$1.$2');
        value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
        value = value.replace(/\.(\d{3})\.(\d{3})(\d)/, '.$1.$2/$3');
        value = value.replace(/(\d{4})(\d)/, '$1-$2');
        value = value.slice(0, 18);
    }

    $(this).val(value);
});