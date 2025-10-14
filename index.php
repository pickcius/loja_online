<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hub de Lojas</title>

  <link href="css/bootstrap.css" rel="stylesheet">

</head>
<body class="bg-light">

  <div class="container py-5">
    <h1 class="text-center mb-4">Hub de Lojas</h1>

    <div class="mb-4">
      <label for="lojaSelect" class="form-label">Escolha uma loja:</label>
      <select id="lojaSelect" class="form-select">
        <option value="">Carregando lojas...</option>
      </select>
    </div>

    <div id="produtosContainer" class="row g-4"></div>
  </div>

  <script>
    const lojaSelect = document.getElementById('lojaSelect');
    const produtosContainer = document.getElementById('produtosContainer');

    // Carrega lojas na dropdown
    fetch('get_lojas.php')
      .then(res => res.json())
      .then(lojas => {
        lojaSelect.innerHTML = '<option value="">Selecione uma loja</option>';
        lojas.forEach(loja => {
          lojaSelect.innerHTML += `<option value="${loja.idLoja}">${loja.Nome}</option>`;
        });
      });

    // Ao mudar loja, buscar produtos
    lojaSelect.addEventListener('change', () => {
      const lojaId = lojaSelect.value;
      produtosContainer.innerHTML = '<p>Carregando produtos...</p>';

      if (!lojaId) return;

      fetch('get_produtos.php?loja=' + lojaId)
        .then(res => res.json())
        .then(produtos => {
          produtosContainer.innerHTML = '';

          if (produtos.length === 0) {
            produtosContainer.innerHTML = '<p class="text-muted">Nenhum produto disponível.</p>';
            return;
          }

          produtos.forEach(prod => {
            const card = `
              <div class="col-md-4">
                <div class="card shadow-sm">
                  <div class="card-body">
                    <h5 class="card-title">${prod.nome}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">R$ ${parseFloat(prod.preco).toFixed(2)}</h6>
                    <ul class="list-group list-group-flush">
                      ${prod.caracteristicas.map(c => `<li class="list-group-item"><strong>${c.nome}:</strong> ${c.valor}</li>`).join('')}
                    </ul>
                  </div>
                </div>
              </div>
            `;
            produtosContainer.innerHTML += card;
          });
        });
    });
  </script>
</body>
</html>
