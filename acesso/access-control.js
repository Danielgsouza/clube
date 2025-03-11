let inputCpf = document.getElementById("socio-id");

document.addEventListener("DOMContentLoaded", function() {
  inputCpf.focus();
});

inputCpf.addEventListener("input", function() {
  if(inputCpf.value.length == 11){
    handleSubmit()
  }
});

inputCpf.addEventListener("focusout", function() {
  setTimeout(() => {
    inputCpf.focus();
  }, 0);
});

var notyf = new Notyf({
  position: {
    x: 'right',
    y: 'top',
  },
});

const handleSubmit = async () => {
  var form = new FormData(document.getElementById("access-control-form"));
  console.log(form)
  try {
    const response = await fetch("./queries/sql_get_access_control.php", {
      method: "POST",
      body: form,
    });
    const dados = await response.json();
    
    if (dados.data.length === 0) {
        // Display an error notification
        notyf.error('Usuário não econtrado e/ou não está cadastrado!');
        inputCpf.value = ''; 
        return;
    }
      
    const socio = dados.data[dados.data.length - 1]; // Última posição do array
    document.getElementById('nome').textContent = socio.nome;
    document.getElementById('cpf').textContent = socio.cpf;
    document.getElementById('status').innerHTML = dados.status ? '<span class="text-success"><i class="fas fa-check text-success"></i> Ativo</span>' : '<span class="text-danger"><i class="fas fa-xmark text-danger"></i> Pendente</span>';

    // document.getElementById('status-icon').innerHTML = dados.status ? '<span class="text-success m-auto" style="font-size: 5rem;"><i class="fas fa-check text-success"></i> </span>' : '<span class="text-danger"><i class="fas fa-xmark text-danger" style="font-size: 5rem;"></i></span>';

    // Tentativa de carregar a imagem com diferentes extensões
    const imagePathBase = `../users/uploads/${socio.cpf}`;
    const imageExtensions = ['.png', '.jpg', '.jpeg']; // Extensões possíveis
    let imagePath = null;

    // Função para verificar se a imagem existe
    function checkImage(extension) {
      return new Promise((resolve, reject) => {
        const img = new Image();
        img.src = `${imagePathBase}${extension}`;
        img.onload = () => resolve(`${imagePathBase}${extension}`); // Retorna o caminho da imagem se carregada com sucesso
        img.onerror = () => reject(); // Rejeita se ocorrer um erro ao carregar a imagem
      });
    }

    // Tentando carregar a imagem com as extensões possíveis
    (async function loadImage() {
      try {
        for (let ext of imageExtensions) {
          try {
            imagePath = await checkImage(ext); // Tenta carregar a imagem
            console.log(imagePath);
            $('#foto-preview').attr('src', imagePath); // Se carregada, exibe a imagem
            return; // Sai da função se a imagem for encontrada
          } catch (error) {
            // Caso a imagem não seja encontrada, tenta a próxima extensão
            continue;
          }
        }
        // Se nenhuma das imagens for encontrada, exibe a imagem padrão
        $('#foto-preview').attr('src', '../assets/images/avatar/icon.png');
      } catch (error) {
        // Caso nenhum arquivo seja encontrado, exibe a imagem padrão
        $('#foto-preview').attr('src', '../assets/images/avatar/icon.png');
      }
    })();

    document.getElementById('foto-preview').onerror = function() {
      this.src = '../assets/images/avatar/icon.png';
    }
    inputCpf.value = ''; // Limpar o campo de pesquisa
    inputCpf.focus(); // Focar no campo de pesquisa após limpar o campo

    // Limpar os dados após 5 segundos
    setTimeout(() => {
      document.getElementById('nome').textContent = '';
      document.getElementById('cpf').textContent = '';
      document.getElementById('status').textContent = '';
      // document.getElementById('status-icon').textContent = '';
      document.getElementById('foto-preview').src = '../assets/images/avatar/icon.png';

    }, 3000);
    // }else{
    //   // Display an error notification
    //   notyf.error('Usuário não econtrado e/ou não está cadastrado!');
    //   inputCpf.value = ''; 
    // }

  }catch (error) {
    console.log(error);
  }
}
