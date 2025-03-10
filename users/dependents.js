var notyf = new Notyf({
  position: {
    x: 'right',
    y: 'top',
  },
});
document.addEventListener("DOMContentLoaded", async function() {
  const urlParams = new URLSearchParams(window.location.search);
  
  // Verifique se existe um ID para determinar se é uma edição ou criação
  const id = urlParams.get('id');
  
  if (id) {

    let titularId = $('input[name="titular_id"]').val(urlParams.get('titular_id'));
    const responseDependents = await getDepedents(titularId);

    dependentsTable(responseDependents);

    // Preencher o formulário com os dados recebidos
    $('input[name="id"]').val("new"); 
    $('input[name="nome"]').val(urlParams.get('nome'));
       
  } else {
    // Configurações para criação de novo sócio
  }
});

document.getElementById('upload-button').addEventListener('click', function() {
  document.getElementById('foto').click();
});

const previewImage = (event) => {
  var reader = new FileReader();
  reader.onload = function(){
    var output = document.getElementById('foto-preview');
    output.src = reader.result;
  };
  reader.readAsDataURL(event.target.files[0]);
  
}

const getDepedents = async (titularId) => {
  try {
    const response = await fetch("./queries/sql_get_dependents.php", {
      method: "POST",
      body: JSON.stringify({titularId: titularId.val() }) 
    });
    const dados = await response.json();

    if (dados.status) {
      return dados;
    }
 
  } catch (error) {
    notyf.error('Erro!', 'Ocorreu um erro ao enviar os dados. Tente novamente.', 'error');
  }
}

const dependentsTable = (associate) => {
  let htmlBody = "";

  if (!associate && !Array.isArray(associate) && associate.length === 0) {
    htmlBody = `<tr><td colspan="6" class="text-center">Nenhum dependente encontrado.</td></tr>`;
    $("#tbody").html(htmlBody);
    return;
  }

  associate.data.forEach(element => {
    var colorBadge = element.status == "1" ? "badge-success" : "badge-danger";
    var text = element.status == "1" ? "Ativo"  : "Inativo"
    htmlBody += `<tr>`;
    htmlBody += `<td><span class='badge rounded-pill ${colorBadge}'>${text}</td>`;
    htmlBody += `<td>${element.nome}</td>`;
    htmlBody += `<td>${element.cpf}</td>`;
    htmlBody += `<td>${element.titulo}</td>`;
    htmlBody += `<td>${element.data_nascimento}</td>`;
    htmlBody += `<td>${element.telefone}</td>`;
    htmlBody += `<td>${element.formatted_date}</td>`;
    htmlBody += `<td>`;
    htmlBody += `<ul>`;
    htmlBody += `<a href="#" class="mx-1 edit-link" data-id="${element.id}" data-nome="${element.nome}" data-cpf="${element.cpf}" data-titulo="${element.titulo}" data-data_nasc="${element.data_nascimento}" data-telefone="${element.telefone}"><i class="fas fa-edit"></i></a>`;
    htmlBody += `</ul>`;
    htmlBody += `</td>`;
    htmlBody += `</tr>`;
  });

  $("#tbody").html(htmlBody);

  $("#table-dependents").DataTable();

  // // Adicione o evento de clique ao botão de edição
  $('.edit-link').on('click', function(event) {
    event.preventDefault();
    const element = $(this).data();
    $('#id').val(element.id);
    $('#nome').val(element.nome);
    $('#cpf').val(element.cpf);
    $('#titulo').val(element.titulo);
    // Converte a data de nascimento para o formato YYYY-MM-DD
    const dataParts = element.data_nasc.split('/');
    const dataNascimento = `${dataParts[2]}-${dataParts[1]}-${dataParts[0]}`;
    $('#nasc').val(dataNascimento);
    $('#telefone').val(element.telefone);

    const imagePath = `../users/uploads/${element.cpf}.png`;

    $('#foto-preview').attr('src', imagePath).on('error', function() {
      $(this).attr('src', '../assets/images/avatar/icon.png');
    });
    // Exiba a div_payments
    $('#div_dependents').show();
  });
};

const cadPagamentoForm = document.getElementById("dependents-form");

cadPagamentoForm.addEventListener("submit", function(e){
  e.preventDefault();
  let cpf = $('input[name="cpf"]').val();
  const dadosForm = new FormData(cadPagamentoForm);
  dadosForm.append('titular_id', cpf);
  Swal.fire({
    title: 'Você tem certeza?',
    text: "Deseja salvar este Pagamento?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sim, salvar!',
    cancelButtonText: 'Cancelar'
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        const response = await fetch("./queries/sql_set_dependents.php", {
          method: "POST",
          body: dadosForm,
        });

        if (!response.ok) {
          throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        const dados = await response;

        if (dados.status) {
          await notyf.success('Dados salvos com sucesso!');
          setTimeout(async () => {
            clearFields()
            $('#div_dependents').hide();
            let cpf = $('input[name="cpf"]').val(urlParams.get('cpf'));
            const responseDependents = await getDepedents(cpf.val());
            await dependentsTable(responseDependents);
          }, 2400);
        } else {
          notyf.error('Erro!', dados.msg);
        }
      } catch (error) {
        notyf.error('Erro!', 'Ocorreu um erro ao enviar os dados. Tente novamente.', 'error');
      }
    }
  });
});


document.getElementById('btn_dependents').addEventListener('click', function() {
  let divDependents = document.getElementById('div_dependents');
  if (divDependents.style.display === 'none' || divDependents.style.display === '') {
    divDependents.style.display = 'block';

    
  } else {
    divDependents.style.display = 'none';
  }
  clearFields();
});

const clearFields = () => {
  $('#mes').val('');
  $('#ano').val('');
  $('#valor').val('');
};