$(document).ready(function() {
    
    // Verifica se estamos na tela de vínculos pegando o ID oculto
    const produtoIdAtual = $('#produto_id_atual').val();

    // Função de Feedback Visual (Toast/Mensagem)
    function mostrarFeedback(mensagem, tipo) {
        let box = $('#msg-feedback');
        box.text(mensagem).removeClass('erro sucesso');
        
        if(tipo === 'erro') {
            box.css({backgroundColor: '#f8d7da', color: '#721c24', border: '1px solid #f5c6cb'}).fadeIn();
        } else {
            box.css({backgroundColor: '#d4edda', color: '#155724', border: '1px solid #c3e6cb'}).fadeIn();
        }
        
        // Some depois de 3 segundos
        setTimeout(function() { box.fadeOut(); }, 3000);
    }

    // 1. Carregar lista de fornecedores vinculados via AJAX
    function carregarVinculos() {
        if(!produtoIdAtual) return; // Só roda se estiver na tela de vínculo

        $.ajax({
            url: '../controllers/VinculoController.php',
            type: 'GET',
            data: { action: 'listar', produto_id: produtoIdAtual },
            dataType: 'json',
            success: function(res) {
                let tbody = $('#tabela-vinculos-body');
                tbody.empty(); // Limpa a tabela

                if(res.sucesso && res.dados.length > 0) {
                    res.dados.forEach(function(fornecedor) {
                        tbody.append(`
                            <tr id="linha-${fornecedor.id}">
                                <td style="text-align: center;"><input type="checkbox" class="check-fornecedor" value="${fornecedor.id}"></td>
                                <td>${fornecedor.id}</td>
                                <td>${fornecedor.nome}</td>
                                <td>${fornecedor.cnpj}</td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append('<tr><td colspan="4" style="text-align: center;">Nenhum fornecedor vinculado a este produto.</td></tr>');
                }
            }
        });
    }

    // 2. Vincular novo Fornecedor
    $('#btn-vincular').on('click', function() {
        let fornId = $('#novo_fornecedor_id').val();
        
        if(!fornId) {
            mostrarFeedback('Informe o ID do fornecedor.', 'erro');
            return;
        }

        $.ajax({
            url: '../controllers/VinculoController.php',
            type: 'POST',
            data: { 
                action: 'vincular', 
                produto_id: produtoIdAtual, 
                fornecedor_id: fornId 
            },
            dataType: 'json',
            success: function(res) {
                if(res.sucesso) {
                    mostrarFeedback(res.mensagem, 'sucesso');
                    $('#novo_fornecedor_id').val(''); // Limpa o input
                    carregarVinculos(); // Recarrega a tabela via AJAX
                } else {
                    mostrarFeedback(res.mensagem, 'erro');
                }
            }
        });
    });

    // 3. Remover em Massa
    $('#btn-remover-massa').on('click', function() {
        let selecionados = [];
        $('.check-fornecedor:checked').each(function() {
            selecionados.push($(this).val());
        });

        if(selecionados.length === 0) {
            mostrarFeedback('Selecione pelo menos um fornecedor para remover.', 'erro');
            return;
        }

        if(confirm('Tem certeza que deseja remover os vínculos selecionados?')) {
            $.ajax({
                url: '../controllers/VinculoController.php',
                type: 'POST',
                data: {
                    action: 'remover_massa',
                    produto_id: produtoIdAtual,
                    fornecedores_ids: selecionados
                },
                dataType: 'json',
                success: function(res) {
                    if(res.sucesso) {
                        mostrarFeedback(res.mensagem, 'sucesso');
                        carregarVinculos(); // Atualiza a tabela na hora
                        $('#check-todos').prop('checked', false);
                    } else {
                        mostrarFeedback(res.mensagem, 'erro');
                    }
                }
            });
        }
    });

    // 4. Selecionar/Deselecionar todos os checkboxes
    $('#check-todos').on('click', function() {
        $('.check-fornecedor').prop('checked', $(this).prop('checked'));
    });

    // Inicia carregando os dados ao abrir a página
    carregarVinculos();

    // ==========================================
    // DESAFIO DE CRIATIVIDADE: FILTRO DE BUSCA (UX)
    // ==========================================
    $('#busca-tabela').on('keyup', function() {
        // Pega o valor digitado e transforma em letras minúsculas
        var valorBusca = $(this).val().toLowerCase();
        
        // Filtra as linhas do corpo da tabela (tbody tr)
        $('table tbody tr').filter(function() {
            // Alterna a exibição da linha se o texto dela bater com a busca
            $(this).toggle($(this).text().toLowerCase().indexOf(valorBusca) > -1);
        });
    });

});