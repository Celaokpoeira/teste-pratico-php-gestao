$(document).ready(function() {
    
    const produtoIdAtual = $('#produto_id_atual').val();

    // Função de Feedback Visual
    function mostrarFeedback(mensagem, tipo) {
        let box = $('#msg-feedback');
        box.text(mensagem).removeClass('erro sucesso');
        
        if(tipo === 'erro') box.addClass('erro').css({color: 'red', fontWeight: 'bold'});
        else box.addClass('sucesso').css({color: 'green', fontWeight: 'bold'});
        
        box.fadeIn().delay(3000).fadeOut();
    }

    // 1. Carregar lista via AJAX
    function carregarVinculos() {
        $.ajax({
            url: 'controllers/VinculoController.php',
            type: 'GET',
            data: { action: 'listar', produto_id: produtoIdAtual },
            dataType: 'json',
            success: function(res) {
                let tbody = $('#tabela-vinculos tbody');
                tbody.empty(); // Limpa a tabela antes de popular

                if(res.sucesso && res.dados.length > 0) {
                    res.dados.forEach(function(fornecedor) {
                        tbody.append(`
                            <tr id="linha-${fornecedor.id}">
                                <td><input type="checkbox" class="check-fornecedor" value="${fornecedor.id}"></td>
                                <td>${fornecedor.id}</td>
                                <td>${fornecedor.nome}</td>
                                <td>${fornecedor.cnpj}</td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append('<tr><td colspan="4">Nenhum fornecedor vinculado.</td></tr>');
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
            url: 'controllers/VinculoController.php',
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
                    carregarVinculos(); // Recarrega a tabela
                } else {
                    mostrarFeedback(res.mensagem, 'erro');
                }
            }
        });
    });

    // 3. Remover em Massa
    $('#btn-remover-massa').on('click', function() {
        // Pega todos os checkboxes que estão marcados
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
                url: 'controllers/VinculoController.php',
                type: 'POST',
                data: {
                    action: 'remover_massa',
                    produto_id: produtoIdAtual,
                    fornecedores_ids: selecionados // Envia como array
                },
                dataType: 'json',
                success: function(res) {
                    if(res.sucesso) {
                        mostrarFeedback(res.mensagem, 'sucesso');
                        carregarVinculos();
                        $('#check-todos').prop('checked', false);
                    } else {
                        mostrarFeedback(res.mensagem, 'erro');
                    }
                }
            });
        }
    });

    // Função utilitária: Selecionar/Deselecionar todos os checkboxes
    $('#check-todos').on('click', function() {
        $('.check-fornecedor').prop('checked', $(this).prop('checked'));
    });

    // Inicia carregando os dados ao abrir a página
    carregarVinculos();
});