<?php
$pageTitle = 'Robótica Autônoma Protegendo Oceanos';
require_once 'includes/header.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$db = Database::getInstance()->getConnection();
$siteUrl = SITE_URL; // Usado no JS
?>

<main>
    <!-- MODAL DE FEEDBACK -->
    <div id="simple-modal" class="fixed inset-0 bg-gray-900 bg-opacity-90 hidden items-center justify-center z-[1000]">
        <div class="p-8 max-w-lg w-full btn-flat" style="background-color: var(--neutral-white); color: var(--neutral-black);">
            <div id="modal-header" class="flex justify-between items-center mb-6 border-b pb-4" style="border-color: var(--accent-cyan);">
                <div class="flex items-center">
                    <i id="modal-icon" class="lucide mr-3"></i>
                    <h3 id="modal-title" class="text-2xl font-bold" style="color: var(--primary-navy);"></h3>
                </div>
                <button onclick="document.getElementById('simple-modal').classList.add('hidden')" class="p-2 hover:bg-gray-100 transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <p id="modal-message" class="text-lg mb-4"></p>
            <button onclick="document.getElementById('simple-modal').classList.add('hidden')" class="w-full px-6 py-3 font-bold text-sm btn-flat" style="background-color: var(--accent-cyan); color: var(--neutral-white);">
                FECHAR
            </button>
        </div>
    </div>
    
    <!-- Seção Hero -->
    <section id="home" class="py-32 btn-flat" style="background-color: var(--primary-navy); color: var(--neutral-white);">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl">
                <div class="w-20 h-1 mb-8 btn-flat" style="background-color: var(--accent-cyan);"></div>
                <h1 class="text-6xl lg:text-7xl font-bold mb-8 leading-tight">
                    Robótica Autônoma<br />Protegendo Oceanos
                </h1>
                <p class="text-xl lg:text-2xl mb-12 leading-relaxed text-gray-300 max-w-2xl">
                    Tecnologia de ponta trabalhando 24/7 para coletar lixo marinho e gerar dados científicos precisos sobre a poluição oceânica em tempo real.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#patrocinio" class="px-10 py-5 font-bold text-sm tracking-wider hover:opacity-90 transition-colors flex items-center justify-center btn-flat" style="background-color: var(--accent-cyan); color: var(--neutral-white);">
                        PATROCINE AGORA
                    </a>
                    <a href="#rastreamento" class="border-2 border-white px-10 py-5 font-bold text-sm tracking-wider hover:bg-white hover:text-[#00204A] transition-colors flex items-center justify-center btn-flat">
                        VER ROBÔS AO VIVO
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção Impacto -->
    <section id="impacto" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <div class="w-20 h-1 mx-auto mb-6 btn-flat" style="background-color: var(--accent-cyan);"></div>
                <h2 class="text-5xl font-bold mb-4" style="color: var(--primary-navy);">Impacto em Tempo Real</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Dados atualizados automaticamente. Transparência total sobre nossa operação.
                </p>
            </div>

            <div id="impact-stats-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Placeholder para carregamento JS -->
                <div class="h-64 bg-gray-200 animate-pulse btn-flat"></div><div class="h-64 bg-gray-200 animate-pulse btn-flat"></div><div class="h-64 bg-gray-200 animate-pulse btn-flat"></div><div class="h-64 bg-gray-200 animate-pulse btn-flat"></div>
            </div>
        </div>
    </section>

    <!-- Seção Rastreamento -->
    <section id="rastreamento" class="py-20 bg-gray-100">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <div class="w-20 h-1 mx-auto mb-6 btn-flat" style="background-color: var(--accent-cyan);"></div>
                <h2 class="text-5xl font-bold mb-4" style="color: var(--primary-navy);">Rastreamento ao Vivo</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Acompanhe a localização exata de cada robô trabalhando agora na costa brasileira.
                </p>
            </div>

            <div class="grid lg:grid-cols-5 gap-8">
                <div id="map-container" class="lg:col-span-3 h-[500px] relative overflow-hidden btn-flat" style="background-color: var(--primary-navy);">
                    <div class="absolute top-6 right-6 px-4 py-2 text-xs font-bold flex items-center gap-2 z-20 btn-flat" style="background-color: var(--accent-cyan); color: var(--neutral-white);">
                        <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                        TRANSMISSÃO AO VIVO
                    </div>
                    <div id="map-grid" class="absolute inset-0 opacity-10"></div>
                </div>

                <div id="robots-panel" class="lg:col-span-2 space-y-4">
                    <div class="h-24 bg-white animate-pulse btn-flat"></div><div class="h-24 bg-white animate-pulse btn-flat"></div><div class="h-24 bg-white animate-pulse btn-flat"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção Pesquisa -->
    <section id="pesquisa" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="max-w-5xl mx-auto">
                <div class="w-20 h-1 mb-6 btn-flat" style="background-color: var(--accent-cyan);"></div>
                <h2 class="text-5xl font-bold mb-6" style="color: var(--primary-navy);">Dados Científicos Abertos</h2>
                <p class="text-lg text-gray-600 mb-12 max-w-3xl">
                    Parceria com universidades e centros de pesquisa. Acesso via API para dados georreferenciados de coleta e análise de microplásticos.
                </p>

                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-gray-100 p-8 btn-flat">
                        <i data-lucide="bar-chart-3" class="w-12 h-12 mb-6" style="color: var(--accent-cyan);"></i>
                        <h3 class="text-2xl font-bold mb-4" style="color: var(--primary-navy);">API de Pesquisa</h3>
                        <p class="text-gray-700 mb-6">
                            Endpoint <code class="bg-white px-2 py-1">/api/research/data</code> com autenticação tokenizada.
                        </p>
                        <button class="px-6 py-3 font-bold text-sm flex items-center gap-2 hover:opacity-90 transition-colors btn-flat" style="background-color: var(--accent-cyan); color: var(--neutral-white);">
                            DOCUMENTAÇÃO API
                            <i data-lucide="external-link" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <div class="bg-gray-100 p-8 btn-flat">
                        <i data-lucide="download" class="w-12 h-12 mb-6" style="color: var(--accent-cyan);"></i>
                        <h3 class="text-2xl font-bold mb-4" style="color: var(--primary-navy);">Relatórios Públicos</h3>
                        <p class="text-gray-700 mb-6">
                            Relatórios anuais de atividades e prestação de contas ESG.
                        </p>
                        <button class="px-6 py-3 font-bold text-sm flex items-center gap-2 hover:opacity-90 transition-colors btn-flat" style="background-color: var(--accent-cyan); color: var(--neutral-white);">
                            BAIXAR RELATÓRIOS
                            <i data-lucide="download" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção Patrocínio -->
    <section id="patrocinio" class="py-20" style="background-color: var(--primary-navy); color: var(--neutral-white);">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <div class="w-20 h-1 mx-auto mb-6 btn-flat" style="background-color: var(--accent-cyan);"></div>
                    <h2 class="text-5xl font-bold mb-4">Patrocínio Corporativo</h2>
                    <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                        Empresas comprometidas com ESG. Associe sua marca à inovação e impacto ambiental real.
                    </p>
                </div>

                <form id="sponsorship-form" class="bg-white text-gray-900 p-10 btn-flat" method="POST" action="api/sponsorship.php">
                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <input type="text" id="nome" name="nome" placeholder="Nome Completo *" required class="px-5 py-4 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
                            <input type="text" id="cargo" name="cargo" placeholder="Cargo *" required class="px-5 py-4 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
                        </div>

                        <input type="text" id="empresa" name="empresa" placeholder="Empresa *" required class="w-full px-5 py-4 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">

                        <div class="grid md:grid-cols-2 gap-6">
                            <input type="email" id="email" name="email" placeholder="E-mail Corporativo *" required class="px-5 py-4 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
                            <input type="tel" id="telefone" name="telefone" placeholder="Telefone *" required class="px-5 py-4 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
                        </div>

                        <select name="orcamento" id="orcamento" required class="w-full px-5 py-4 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
                            <option value="">Orçamento Estimado *</option>
                            <option value="10k-50k">R$ 10.000 - R$ 50.000</option>
                            <option value="50k-100k">R$ 50.000 - R$ 100.000</option>
                            <option value="100k-500k">R$ 100.000 - R$ 500.000</option>
                            <option value="500k+">Acima de R$ 500.000</option>
                        </select>

                        <!-- BLOCO DE IA PARA GERAÇÃO DE MENSAGEM (Integração Gemini) -->
                        <div class='border-2 border-gray-200 p-4 btn-flat'>
                            <p class='text-sm font-bold mb-3' style="color: var(--primary-navy);">✨ Ferramenta de Rascunho ESG (IA)</p>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <input type="text" id="llm-input" placeholder="Focos ESG da Empresa (Ex: Inovação, Biodiversidade) *" class="w-full px-4 py-3 border-2 border-gray-300 focus:border-[#00A896] outline-none text-base btn-flat">
                                <button type="button" id="generate-draft-btn" class="px-4 py-3 font-bold text-sm tracking-wider flex items-center justify-center transition-colors whitespace-nowrap btn-flat" style="background-color: var(--accent-cyan); color: var(--neutral-white);">
                                    <i data-lucide="sparkles" class="w-5 h-5 mr-1"></i>
                                    GERAR RASCUNHO
                                </button>
                            </div>
                            <div id="llm-loading-state" class='mt-4 text-center py-2 text-sm font-semibold hidden' style="color: var(--accent-cyan);">
                                IA PENSANDO... Pode levar até 10 segundos.
                            </div>
                            <div id="llm-citations" class='mt-4 pt-3 border-t border-gray-300 hidden'>
                                <p class='text-xs font-bold' style="color: var(--primary-navy);">Fontes (Pesquisa Google para ESG):</p>
                                <ul id="citations-list" class='text-xs text-gray-700 list-disc list-inside space-y-1'></ul>
                            </div>
                        </div>

                        <textarea name="mensagem" id="mensagem" placeholder="Mensagem: O que sua empresa busca patrocinar? *" rows="5" required class="w-full px-5 py-4 border-2 border-gray-300 focus:border-[#00A896] outline-none resize-none btn-flat"></textarea>

                        <button type="submit" class="w-full px-10 py-5 font-bold text-sm tracking-wider hover:opacity-90 transition-colors flex items-center justify-center gap-3 btn-flat" style="background-color: var(--accent-cyan); color: var(--neutral-white);">
                            ENVIAR PROPOSTA
                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<script>
const SITE_URL = '<?php echo $siteUrl; ?>';

// --- GESTÃO DE MODAL ---
function openModal(isSuccess, title, message) {
    const modal = document.getElementById('simple-modal');
    document.getElementById('modal-title').textContent = title;
    document.getElementById('modal-message').textContent = message;
    
    const iconElement = document.getElementById('modal-icon');
    iconElement.setAttribute('data-lucide', isSuccess ? 'check' : 'alert-triangle');
    iconElement.style.color = isSuccess ? 'var(--accent-cyan)' : '#DC2626';

    window.lucide.createIcons();
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// --- FUNÇÃO DE CARREGAMENTO DE DADOS E RENDERIZAÇÃO (JS) ---

async function loadStats() {
    try {
        const response = await fetch(SITE_URL + '/api/stats.php');
        const data = await response.json();
        
        const container = document.getElementById('impact-stats-container');
        if (data.error) {
             container.innerHTML = '<p class="text-center text-red-500 col-span-4">Erro ao carregar estatísticas. Verifique a conexão com o banco de dados.</p>';
             return;
        }

        container.innerHTML = `
            <div class="bg-white p-10 text-center border-b-4 btn-flat" style="border-color: var(--accent-cyan);">
                <i data-lucide="droplet" class="w-14 h-14 mx-auto mb-6" style="color: var(--accent-cyan);"></i>
                <div class="text-5xl font-bold mb-3" style="color: var(--primary-navy);">${(data.lixo_coletado || 0).toLocaleString('pt-BR')}</div>
                <div class="text-sm text-gray-600 uppercase tracking-widest font-semibold">KG Lixo Coletado</div>
            </div>
            <div class="bg-white p-10 text-center border-b-4 btn-flat" style="border-color: var(--accent-cyan);">
                <i data-lucide="waves" class="w-14 h-14 mx-auto mb-6" style="color: var(--accent-cyan);"></i>
                <div class="text-5xl font-bold mb-3" style="color: var(--primary-navy);">${parseFloat(data.km_limpos || 0).toFixed(1)}</div>
                <div class="text-sm text-gray-600 uppercase tracking-widest font-semibold">KM Oceano Limpos</div>
            </div>
            <div class="bg-white p-10 text-center border-b-4 btn-flat" style="border-color: var(--accent-cyan);">
                <i data-lucide="anchor" class="w-14 h-14 mx-auto mb-6" style="color: var(--accent-cyan);"></i>
                <div class="text-5xl font-bold mb-3" style="color: var(--primary-navy);">${data.robos_ativos || 0}</div>
                <div class="text-sm text-gray-600 uppercase tracking-widest font-semibold">Robôs Ativos Agora</div>
            </div>
            <div class="bg-white p-10 text-center border-b-4 btn-flat" style="border-color: var(--accent-cyan);">
                <i data-lucide="bar-chart-3" class="w-14 h-14 mx-auto mb-6" style="color: var(--accent-cyan);"></i>
                <div class="text-5xl font-bold mb-3" style="color: var(--primary-navy);">${data.pesquisas_ativas || 0}</div>
                <div class="text-sm text-gray-600 uppercase tracking-widest font-semibold">Pesquisas em Curso</div>
            </div>
        `;
        window.lucide.createIcons();
    } catch (error) {
        console.error('Erro ao carregar estatísticas:', error);
    }
}

async function loadRobots() {
    try {
        const response = await fetch(SITE_URL + '/api/robots.php');
        const robots = await response.json();
        
        const mapContainer = document.getElementById('map-container');
        const panel = document.getElementById('robots-panel');
        
        if (robots.error) {
            panel.innerHTML = '<p class="p-4 text-red-500">Erro ao carregar robôs.</p>';
            return;
        }

        // Limpa painel e remove marcadores antigos (exceto o indicador LIVE)
        panel.innerHTML = '';
        mapContainer.querySelectorAll('button:not(.z-20)').forEach(el => el.remove()); 

        // Cria grid do mapa (para a simulação visual)
        const grid = document.getElementById('map-grid');
        grid.innerHTML = '';
        for (let i = 0; i < 15; i++) {
            grid.innerHTML += `<div class="absolute w-full border-t opacity-10" style="top: ${(i * 6.67)}%; border-color: var(--accent-cyan);"></div>`;
            grid.innerHTML += `<div class="absolute h-full border-l opacity-10" style="left: ${(i * 6.67)}%; border-color: var(--accent-cyan);"></div>`;
        }
        
        robots.forEach(robot => {
            // Normalizar coordenadas para o mapa (simulação visual na área 100x100)
            const mapLat = 10 + (Math.abs(robot.lat + 7.5) * 1000) % 80;
            const mapLng = 10 + (Math.abs(robot.lng + 35) * 1000) % 80;
            
            // Marcador no mapa
            const marker = document.createElement('button');
            marker.className = 'absolute transform -translate-x-1/2 -translate-y-1/2 w-10 h-10 hover:opacity-80 transition-all hover:scale-110 flex items-center justify-center group z-10 btn-flat';
            marker.style.cssText = `left: ${mapLng}%; top: ${mapLat}%; background-color: var(--accent-cyan);`;
            marker.innerHTML = `
                <i data-lucide="anchor" class="w-6 h-6 text-white"></i>
                <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs font-bold whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity btn-flat" style="background-color: var(--accent-cyan); color: white;">
                    ${robot.nome}
                </div>
            `;
            mapContainer.appendChild(marker);
            
            // Card no painel
            const statusClass = robot.status === 'operacao' ? 'bg-green-100 text-green-800' : (robot.status === 'carregando' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
            const statusText = robot.status === 'operacao' ? 'EM OPERAÇÃO' : (robot.status === 'carregando' ? 'CARREGANDO' : 'MANUTENÇÃO');
            
            const card = document.createElement('div');
            card.className = 'bg-white p-5 cursor-pointer hover:shadow-lg transition-all btn-flat';
            card.innerHTML = `
                <div class="flex items-start justify-between mb-3">
                    <div class="font-bold text-lg" style="color: var(--primary-navy);">${robot.nome}</div>
                    <span class="text-xs px-3 py-1 font-bold btn-flat ${statusClass}">${statusText}</span>
                </div>
                <div class="space-y-2 text-sm text-gray-700">
                    <div class="flex justify-between"><span>KM Limpos:</span><span class="font-bold" style="color: var(--primary-navy);">${parseFloat(robot.km_limpos).toFixed(1)} km</span></div>
                    <div class="flex justify-between"><span>Lixo Coletado:</span><span class="font-bold" style="color: var(--primary-navy);">${robot.lixo_coletado} kg</span></div>
                    ${robot.patrocinador ? `<div class="flex justify-between pt-2 border-t border-gray-200"><span>Patrocinador:</span><span class="font-bold" style="color: var(--accent-cyan);">${robot.patrocinador}</span></div>` : ''}
                </div>
            `;
            panel.appendChild(card);
        });
        
        window.lucide.createIcons();
    } catch (error) {
        console.error('Erro ao carregar robôs:', error);
    }
}

// --- FUNÇÃO GEMINI (Chamando o endpoint PHP) ---
async function generateProposalDraft() {
    const empresa = document.getElementById('empresa').value.trim();
    const llmInput = document.getElementById('llm-input').value.trim();
    const mensagemInput = document.getElementById('mensagem');
    const loadingState = document.getElementById('llm-loading-state');
    const citationsDiv = document.getElementById('llm-citations');
    const citationsList = document.getElementById('citations-list');
    const btn = document.getElementById('generate-draft-btn');

    if (!empresa || !llmInput) {
        openModal(false, "Entrada Inválida", "Por favor, preencha o nome da empresa e os focos ESG antes de gerar o rascunho.");
        return;
    }

    btn.disabled = true;
    loadingState.classList.remove('hidden');
    citationsDiv.classList.add('hidden');
    citationsList.innerHTML = '';
    mensagemInput.value = "Gerando rascunho... (Usando IA e pesquisa em tempo real) Por favor, aguarde.";
    
    // Preparar dados para o endpoint PHP
    const formData = new FormData();
    formData.append('empresa', empresa);
    formData.append('llm_input', llmInput);

    try {
        const response = await fetch(SITE_URL + '/api/gemini_draft.php', {
            method: 'POST',
            body: formData 
        });

        const result = await response.json();

        if (!response.ok || result.error) {
            throw new Error(result.error || 'Erro desconhecido na API PHP/Gemini.');
        }

        // Sucesso: Atualiza o campo de mensagem
        mensagemInput.value = result.draft;

        // Atualiza as citações
        if (result.citations && result.citations.length > 0) {
            result.citations.forEach(source => {
                citationsList.innerHTML += `<li><a href="${source.uri}" target="_blank" rel="noopener noreferrer" class="hover:text-accent-cyan underline">${source.title}</a></li>`;
            });
            citationsDiv.classList.remove('hidden');
        }

    } catch (error) {
        console.error("Erro na Geração de IA:", error);
        mensagemInput.value = "Desculpe, houve um erro na geração de IA. Verifique se a chave GEMINI_API_KEY está correta no includes/config.php.";
        openModal(false, "Erro de Geração de IA", "Não foi possível gerar o rascunho da proposta.");
    } finally {
        btn.disabled = false;
        loadingState.classList.add('hidden');
    }
}
document.getElementById('generate-draft-btn')?.addEventListener('click', generateProposalDraft);

// --- SUBMISSÃO DO FORMULÁRIO DE PATROCÍNIO (Chamando endpoint PHP) ---
document.getElementById('sponsorship-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const button = this.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    button.innerHTML = 'ENVIANDO...';
    button.disabled = true;
    
    try {
        const formData = new FormData(this);
        const response = await fetch(SITE_URL + '/api/sponsorship.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            openModal(true, "Proposta Enviada com Sucesso!", "Agradecemos o seu interesse. Nossa equipe entrará em contato em até 48 horas úteis.");
            this.reset();
        } else {
            openModal(false, "Erro ao Enviar Proposta", result.message || 'Tente novamente.');
        }
    } catch (error) {
        openModal(false, "Erro de Rede", "Falha ao comunicar com o servidor. Verifique sua conexão.");
    } finally {
        button.innerHTML = originalText;
        button.disabled = false;
        window.lucide.createIcons();
    }
});

// Carregar dados ao iniciar
document.addEventListener('DOMContentLoaded', function() {
    loadStats();
    loadRobots();
    
    // Atualizar a cada 30 segundos (simulando tempo real)
    setInterval(loadStats, 30000);
    setInterval(loadRobots, 30000);
});
</script>

<?php require_once 'includes/footer.php'; ?>
