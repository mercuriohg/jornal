<div class="faq-widget">
    <div id="faq-panel" class="faq-panel" hidden>
        <div class="faq-panel-header">
            <span>Dúvidas frequentes</span>
            <button type="button" class="faq-close" id="faq-close" aria-label="Fechar dúvidas frequentes">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="faq-panel-content">
            <details open>
                <summary>Como entrar em contato?</summary>
                <p>Você pode usar o formulário da página de contato ou enviar um e-mail diretamente para o endereço informado.</p>
            </details>
            <details>
                <summary>Onde encontro as notícias?</summary>
                <p>As notícias ficam disponíveis na página inicial e na seção de notícias do site.</p>
            </details>
            <details>
                <summary>Como acompanhar os projetos?</summary>
                <p>Os projetos estão organizados em uma página específica, com informações e atualizações.</p>
            </details>
        </div>
    </div>

    <button type="button" class="faq-fab" id="faq-toggle" aria-expanded="false" aria-controls="faq-panel">
        <i class="fa-solid fa-circle-question"></i>
        <span>Dúvidas frequentes</span>
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('faq-toggle');
        const panel = document.getElementById('faq-panel');
        const close = document.getElementById('faq-close');

        if (!toggle || !panel) {
            return;
        }

        const togglePanel = function () {
            const isOpen = panel.hidden;
            panel.hidden = !isOpen;
            toggle.setAttribute('aria-expanded', String(isOpen));
        };

        toggle.addEventListener('click', togglePanel);

        if (close) {
            close.addEventListener('click', togglePanel);
        }
    });
</script>