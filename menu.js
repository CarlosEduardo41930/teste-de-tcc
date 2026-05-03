document.addEventListener("DOMContentLoaded", () => {

    // Função do menu hamburger
    function toggleSidebar() {
        const icon = document.querySelector(".topbar");
        const sidebar = document.getElementById("sidebar");

        if (!icon || !sidebar) return;

        icon.addEventListener("click", () => {
            icon.classList.toggle("active");
            sidebar.classList.toggle("active");
        });
    }

    // Função para calcular idade
    function calcularIdade(dataNascimento) {
        const hoje = new Date();
        const nascimento = new Date(dataNascimento);

        let idade = hoje.getFullYear() - nascimento.getFullYear();
        const mes = hoje.getMonth() - nascimento.getMonth();

        if (mes < 0 || (mes === 0 && hoje.getDate() < nascimento.getDate())) {
            idade--;
        }

        return idade;
    }

    // Formatar data para pt-BR
    function formatarData(data) {
        const d = new Date(data);
        return d.toLocaleDateString("pt-BR");
    }

    // Função de editar campos
    function editableCampos() {
        document.querySelectorAll(".campo").forEach(campo => {
            const texto = campo.querySelector(".texto");
            const input = campo.querySelector(".input");
            const btnEditar = campo.querySelector(".editar");
            const btnSalvar = campo.querySelector(".salvar");

            // pular se não tiver edição
            if (!btnEditar || !btnSalvar || !input) return;

            btnEditar.addEventListener("click", () => {
                texto.classList.add("hidden");
                input.classList.remove("hidden");
                btnEditar.classList.add("hidden");
                btnSalvar.classList.remove("hidden");
            });

            btnSalvar.addEventListener("click", () => {

                // Se for data → formata
                if (input.type === "date") {
                    texto.textContent = formatarData(input.value);

                    // Atualiza idade automaticamente
                    const campoIdade = document.querySelector(".idade");
                    if (campoIdade) {
                        campoIdade.textContent = calcularIdade(input.value);
                    }
                } else {
                    texto.textContent = input.value;
                }

                texto.classList.remove("hidden");
                input.classList.add("hidden");
                btnEditar.classList.remove("hidden");
                btnSalvar.classList.add("hidden");
            });
        });
    }

    // 🔹 Atualizar idade ao carregar a página
    function atualizarIdadeInicial() {
        const inputData = document.querySelector('input[type="date"]');
        const campoIdade = document.querySelector(".idade");

        if (inputData && campoIdade) {
            campoIdade.textContent = calcularIdade(inputData.value);
        }
    }

    // 🔹 Chamando tudo
    toggleSidebar();
    editableCampos();
    atualizarIdadeInicial();

});