const getSQL = async (url) => {
  try {
    const response = await fetch(url, {
      method: "GET",
    })
    return await response.json();
  } catch (error) {
    console.error('Erro ao enviar os dados:', error);
    Swal.fire('Erro!', 'Ocorreu um erro ao carregar os dados. Tente novamente.', 'error');
  }
}

const checkImage = (extension, imagePathBase) => {
  return new Promise((resolve, reject) => {
    const img = new Image();
    img.src = `${imagePathBase}${extension}`;
    img.onload = () => resolve(`${imagePathBase}${extension}`); // Retorna o caminho da imagem se carregada com sucesso
    img.onerror = () => reject(); // Rejeita se ocorrer um erro ao carregar a imagem
  });
};

const loadImage = async (imagePathBase, imageExtensions, imgElement) => {
  try {
    for (let ext of imageExtensions) {
      try {
        const imagePath = await checkImage(ext, imagePathBase);
        imgElement.src = imagePath; // Exibe a imagem
        return;
      } catch (error) {
        continue;
      }
    }
    // Se nenhuma imagem for encontrada, exibe a imagem padrão
    imgElement.src = '../assets/images/avatar/icon.png';
  } catch (error) {
    imgElement.src = '../assets/images/avatar/icon.png';
  }
};

const downloadImage = (element, containerId) => {
  const cardContainer = document.getElementById(containerId);
  if (!cardContainer) {
    console.error('Elemento card-container não encontrado.');
    return;
  }

  html2canvas(cardContainer).then(canvas => {
    const imgData = canvas.toDataURL('image/png');
    const link = document.createElement('a');
    link.href = imgData;
    link.download = `[${element.cpf}] ${element.nome} - carteirinha.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }).catch(error => {
    console.error('Erro ao capturar o elemento com html2canvas:', error);
  });
};