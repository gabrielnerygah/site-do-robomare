<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instituto Maré Futuro - Robótica para o Oceano</title>
    <!-- Carregamento do Tailwind CSS via CDN (Design Minimalista e Flat) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Configuração da Fonte Inter -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* Definição de Cores em CSS */
        :root {
            --primary-navy: #00204A;
            --accent-cyan: #00A896;
            --neutral-white: #FFFFFF;
            --neutral-black: #000000;
        }
        /* Garantindo que não haja bordas arredondadas (Flat Design) */
        .btn-flat {
            border-radius: 0 !important;
        }
    </style>

    <!-- Ícones Lucide para HTML (usando um script wrapper) -->
    <script type="module">
        import { createIcons, Waves, Droplet, Anchor, BarChart3, ChevronRight, Download, ExternalLink, Menu, X, Check, AlertTriangle, Sparkles } from 'https://unpkg.com/lucide@latest?module';
        createIcons({ icons: { Waves, Droplet, Anchor, BarChart3, ChevronRight, Download, ExternalLink, Menu, X, Check, AlertTriangle, Sparkles } });
    </script>

    <!-- Firebase SDKs (Requeridos para Firestore e Auth) -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
        import { getAuth, signInAnonymously, signInWithCustomToken, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-auth.js";
        import { getFirestore, doc, getDoc, addDoc, setDoc, onSnapshot, collection, query } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-firestore.js";

        window.firebase = {
            initializeApp,
            getAuth, signInAnonymously, signInWithCustomToken, onAuthStateChanged,
            getFirestore, doc, getDoc, addDoc, setDoc, onSnapshot, collection, query
        };
    </script>
</head>

<body class="min-h-screen bg-white">
    <!-- Componente de Modal -->
    <div id="simple-modal" class="fixed inset-0 bg-gray-900 bg-opacity-90 hidden items-center justify-center z-[1000]">
        <div class="p-8 max-w-lg w-full" style="background-color: var(--neutral-white); color: var(--neutral-black);">
            <div id="modal-header" class="flex justify-between items-center mb-6 border-b pb-4" style="border-color: var(--accent-cyan);">
                <div class="flex items-center">
                    <i id="modal-icon" class="lucide mr-3"></i>
                    <h3 id="modal-title" class="text-2xl font-bold" style="color: var(--primary-navy);"></h3>
                </div>
                <button onclick="closeModal()" class="p-2 hover:bg-gray-100 transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <p id="modal-message" class="text-lg mb-4"></p>
            <button onclick="closeModal()" class="w-full px-6 py-3 font-bold text-sm btn-flat" style="background-color: var(--accent-cyan); color: var(--neutral-white);">
                FECHAR
            </button>
        </div>
    </div>

    <!-- Cabeçalho (Header) -->
    <header class="sticky top-0 z-50 border-b-2 shadow-lg" style="background-color: var(--primary-navy); color: var(--neutral-white); border-color: var(--accent-cyan);">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center gap-3">
                    <i data-lucide="waves" class="w-8 h-8" style="color: var(--accent-cyan);"></i>
                    <div class="text-xl font-bold tracking-wider">INSTITUTO MARÉ FUTURO</div>
                </div>
                
                <!-- Navegação Desktop -->
                <nav class="hidden lg:flex items-center gap-10">
                    <a href="#home" class="text-sm font-semibold tracking-wide hover:text-accent-cyan transition-colors" style="color: var(--neutral-white);">HOME</a>
                    <a href="#impacto" class="text-sm font-semibold tracking-wide hover:text-accent-cyan transition-colors" style="color: var(--neutral-white);">IMPACTO</a>
                    <a href="#rastreamento" class="text-sm font-semibold tracking-wide hover:text-accent-cyan transition-colors" style="color: var(--neutral-white);">RASTREAMENTO</a>
                    <a href="#pesquisa" class="text-sm font-semibold tracking-wide hover:text-accent-cyan transition-colors" style="color: var(--neutral-white);">PESQUISA</a>
                    <a href="#patrocinio" class="text-sm font-semibold tracking-wide hover:text-accent-cyan transition-colors" style="color: var(--neutral-white);">PATROCÍNIO</a>
                </nav>

                <!-- Menu Mobile Botão -->
                <button id="menu-button" class="lg:hidden" aria-label="Menu">
                    <i data-lucide="menu" class="w-7 h-7"></i>
                </button>
            </div>

            <!-- Menu Aberto Mobile -->
            <nav id="mobile-menu" class="lg:hidden hidden pb-6 space-y-4 border-t pt-6 mt-2" style="border-color: var(--accent-cyan);">
                <a href="#home" class="block text-base font-semibold tracking-wide" onclick="toggleMenu()">HOME</a>
                <a href="#impacto" class="block text-base font-semibold tracking-wide" onclick="toggleMenu()">IMPACTO</a>
                <a href="#rastreamento" class="block text-base font-semibold tracking-wide" onclick="toggleMenu()">RASTREAMENTO</a>
                <a href="#pesquisa" class="block text-base font-semibold tracking-wide" onclick="toggleMenu()">PESQUISA</a>
                <a href="#patrocinio" class="block text-base font-semibold tracking-wide" onclick="toggleMenu()">PATROCÍNIO</a>
            </nav>
        </div>
    </header>

    <main>
        <!-- Seção Home (Hero) -->
        <section id="home" class="py-32" style="background-color: var(--primary-navy); color: var(--neutral-white);">
            <div class="container mx-auto px-6">
                <div class="max-w-4xl">
                    <div class="w-20 h-1 mb-8 btn-flat" style="background-color: var(--accent-cyan);"></div>
                    <h1 class="text-6xl lg:text-7xl font-bold mb-8 leading-tight">
                        Robótica Autônoma<br />Protegendo Oceanos
                    </h1>
                    <p class="text-xl lg:text-2xl mb-12 leading-relaxed text-neutral-300 max-w-2xl">
                        Tecnologia de ponta trabalhando 24/7 para coletar lixo marinho e gerar dados científicos precisos sobre a poluição oceânica em tempo real.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#patrocinio" class="px-10 py-5 font-bold text-sm tracking-wider hover:opacity-90 transition-colors btn-flat flex items-center justify-center" style="background-color: var(--accent-cyan); color: var(--neutral-white);">
                            PATROCINE AGORA
                        </a>
                        <a href="#rastreamento" class="border-2 px-10 py-5 font-bold text-sm tracking-wider hover:bg-neutral-white hover:text-primary-navy transition-colors btn-flat flex items-center justify-center" style="border-color: var(--neutral-white); color: var(--neutral-white);">
                            VER ROBÔS AO VIVO
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção Impacto (Estatísticas) -->
        <section id="impacto" class="py-20 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <div class="w-20 h-1 mx-auto mb-6 btn-flat" style="background-color: var(--accent-cyan);"></div>
                    <h2 class="text-5xl font-bold mb-4" style="color: var(--primary-navy);">Impacto em Tempo Real</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Dados atualizados automaticamente via Firestore. Transparência total sobre nossa operação e resultados.
                    </p>
                </div>

                <div id="impact-stats-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Estatísticas serão injetadas aqui via JS -->
                    <div class="h-64 bg-gray-200 animate-pulse btn-flat"></div>
                    <div class="h-64 bg-gray-200 animate-pulse btn-flat"></div>
                    <div class="h-64 bg-gray-200 animate-pulse btn-flat"></div>
                    <div class="h-64 bg-gray-200 animate-pulse btn-flat"></div>
                </div>
            </div>
        </section>

        <!-- Seção Rastreamento (Mapa Simulado) -->
        <section id="rastreamento" class="py-20 bg-gray-100">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <div class="w-20 h-1 mx-auto mb-6 btn-flat" style="background-color: var(--accent-cyan);"></div>
                    <h2 class="text-5xl font-bold mb-4" style="color: var(--primary-navy);">Rastreamento ao Vivo</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Acompanhe a localização exata de cada robô trabalhando agora. Transparência é nossa prioridade.
                    </p>
                </div>

                <div class="grid lg:grid-cols-5 gap-8">
                    <!-- Mapa Simulado (Será preenchido dinamicamente) -->
                    <div id="map-container" class="lg:col-span-3 h-[500px] relative overflow-hidden btn-flat" style="background-color: var(--primary-navy);">
                        <div class="absolute top-6 right-6 px-4 py-2 text-xs font-bold flex items-center gap-2 z-20" style="background-color: var(--accent-cyan); color: var(--neutral-white);">
                            <div class="w-2 h-2 bg-white animate-pulse"></div>
                            TRANSMISSÃO AO VIVO
                        </div>
                        <div class="absolute inset-0 z-0 opacity-10" id="map-grid">
                            <!-- Grid será gerado pelo JS -->
                        </div>
                    </div>

                    <!-- Painel de Detalhes dos Robôs -->
                    <div id="robots-panel" class="lg:col-span-2 space-y-4">
                        <!-- Robôs serão injetados aqui via JS -->
                        <div class="h-24 bg-white animate-pulse btn-flat"></div>
                        <div class="h-24 bg-white animate-pulse btn-flat"></div>
                        <div class="h-24 bg-white animate-pulse btn-flat"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção Pesquisa e Dados -->
        <section id="pesquisa" class="py-20 bg-white">
            <div class="container mx-auto px-6">
                <div class="max-w-5xl mx-auto">
                    <div class="w-20 h-1 mb-6 btn-flat" style="background-color: var(--accent-cyan);"></div>
                    <h2 class="text-5xl font-bold mb-6" style="color: var(--primary-navy);">Dados Científicos Abertos</h2>
                    <p class="text-lg text-gray-600 mb-12 max-w-3xl">
                        Nossa robótica gera dados georreferenciados cruciais para estudos de microplásticos e poluição.
                    </p>

                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="bg-gray-100 p-8 btn-flat">
                            <i data-lucide="bar-chart-3" class="w-12 h-12 mb-6" style="color: var(--accent-cyan);"></i>
                            <h3 class="text-2xl font-bold mb-4" style="color: var(--primary-navy);">API de Pesquisa (Premium)</h3>
                            <p class="text-gray-700 mb-6">
                                Endpoint <code class="bg-white px-2 py-1 text-sm text-primary-navy btn-flat">/api/research/data</code> com autenticação tokenizada para acesso a dados brutos.
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
                                Relatórios anuais de atividades e prestação de contas (ESG).
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

        <!-- Seção Patrocínio e Formulário -->
        <section id="patrocinio" class="py-20" style="background-color: var(--primary-navy); color: var(--neutral-white);">
            <div class="container mx-auto px-6">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-12">
                        <div class="w-20 h-1 mx-auto mb-6 btn-flat" style="background-color: var(--accent-cyan);"></div>
                        <h2 class="text-5xl font-bold mb-4">Patrocínio Corporativo</h2>
                        <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                            Associe sua marca à inovação e impacto ambiental real.
                        </p>
                    </div>

                    <form id="sponsorship-form" class="bg-white text-gray-900 p-10 btn-flat">
                        <div class="space-y-6">
                            <!-- Campos de Formulário -->
                            <div class="grid md:grid-cols-2 gap-6">
                                <input type="text" id="nome" placeholder="Nome Completo *" required class="px-5 py-4 border-2 border-gray-300 focus:border-accent-cyan outline-none text-base btn-flat">
                                <input type="text" id="cargo" placeholder="Cargo *" required class="px-5 py-4 border-2 border-gray-300 focus:border-accent-cyan outline-none text-base btn-flat">
                            </div>

                            <input type="text" id="empresa" placeholder="Empresa *" required class="w-full px-5 py-4 border-2 border-gray-300 focus:border-accent-cyan outline-none text-base btn-flat">

                            <div class="grid md:grid-cols-2 gap-6">
                                <input type="email" id="email" placeholder="E-mail Corporativo *" required class="px-5 py-4 border-2 border-gray-300 focus:border-accent-cyan outline-none text-base btn-flat">
                                <input type="tel" id="telefone" placeholder="Telefone *" required class="px-5 py-4 border-2 border-gray-300 focus:border-accent-cyan outline-none text-base btn-flat">
                            </div>

                            <select id="orcamento" required class="w-full px-5 py-4 border-2 border-gray-300 focus:border-accent-cyan outline-none text-base btn-flat">
                                <option value="" disabled selected>Orçamento Estimado *</option>
                                <option value="10k-50k">R$ 10.000 - R$ 50.000</option>
                                <option value="50k-100k">R$ 50.000 - R$ 100.000</option>
                                <option value="100k-500k">R$ 100.000 - R$ 500.000</option>
                                <option value="500k+">Acima de R$ 500.000</option>
                            </select>

                            <!-- BLOCO DE IA PARA GERAÇÃO DE MENSAGEM -->
                            <div class='border-2 border-gray-200 p-4 btn-flat'>
                                <p class='text-sm font-bold mb-3' style="color: var(--primary-navy);">✨ Ferramenta de Rascunho ESG</p>
                                <div class="flex gap-3">
                                    <input type="text" id="llm-input" placeholder="Focos ESG da Empresa (Ex: Inovação, Biodiversidade) *" class="w-full px-4 py-3 border-2 border-gray-300 focus:border-accent-cyan outline-none text-base btn-flat">
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

                            <textarea id="mensagem" placeholder="Mensagem: O que sua empresa busca patrocinar? *" rows="5" required class="w-full px-5 py-4 border-2 border-gray-300 focus:border-accent-cyan outline-none resize-none text-base btn-flat"></textarea>

                            <button type="submit" id="submit-btn" class="w-full px-10 py-5 font-bold text-sm tracking-wider hover:opacity-90 transition-colors flex items-center justify-center gap-3 btn-flat" style="background-color: var(--accent-cyan); color: var(--neutral-white);">
                                ENVIAR PROPOSTA
                                <i data-lucide="chevron-right" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- Rodapé (Footer) -->
    <footer class="py-16" style="background-color: var(--neutral-black); color: var(--neutral-white);">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div>
                    <div class="flex items-center gap-2 mb-6">
                        <i data-lucide="waves" class="w-8 h-8" style="color: var(--accent-cyan);"></i>
                        <div class="font-bold text-lg">MARÉ FUTURO</div>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Robótica e ciência trabalhando pela preservação dos oceanos.
                    </p>
                </div>
                <!-- Navegação e Transparência (omissos para brevidade) -->
            </div>
            <div class="border-t pt-8" style="border-color: #333;">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
                    <div>© 2025 Instituto Maré Futuro. Todos os direitos reservados.</div>
                </div>
            </div>
        </div>
    </footer>

    <!-- LÓGICA JAVASCRIPT (Cliente) -->
    <script type="module">
        // Variáveis globais para Firebase e LLM
        let db, auth, userId = 'default-user';
        let isFirebaseReady = false;
        const appId = typeof __app_id !== 'undefined' ? __app_id : 'default-app-id';
        const MAX_RETRIES = 5;
        const INITIAL_DELAY = 1000;
        const API_KEY = ""; // Será fornecida pelo ambiente
        const API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-05-20:generateContent?key=${API_KEY}`;
        
        // --- GESTÃO DE MODAL ---
        window.openModal = (isSuccess, title, message) => {
            const modal = document.getElementById('simple-modal');
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-message').textContent = message;
            
            const iconElement = document.getElementById('modal-icon');
            iconElement.setAttribute('data-lucide', isSuccess ? 'check' : 'alert-triangle');
            iconElement.style.color = isSuccess ? 'var(--accent-cyan)' : '#DC2626'; // Vermelho

            // Re-renderiza o ícone do lucide
            const lucide = window.lucide;
            if (lucide && lucide.createIcons) {
                lucide.createIcons({ icons: lucide.icons });
            }

            modal.style.display = 'flex';
        };

        window.closeModal = () => {
            document.getElementById('simple-modal').style.display = 'none';
        };

        // --- AUTENTICAÇÃO E INICIALIZAÇÃO FIREBASE ---
        window.onload = async () => {
            if (window.firebase) {
                try {
                    const firebaseConfig = typeof __firebase_config !== 'undefined' ? JSON.parse(__firebase_config) : {};
                    const app = window.firebase.initializeApp(firebaseConfig);
                    db = window.firebase.getFirestore(app);
                    auth = window.firebase.getAuth(app);
                    
                    // Tentativa de autenticação
                    try {
                        if (typeof __initial_auth_token !== 'undefined' && __initial_auth_token !== '') {
                            await window.firebase.signInWithCustomToken(auth, __initial_auth_token);
                        } else {
                            await window.firebase.signInAnonymously(auth);
                        }
                    } catch (error) {
                        console.error("Authentication Error, attempting anonymous fallback:", error);
                        await window.firebase.signInAnonymously(auth);
                    }

                    window.firebase.onAuthStateChanged(auth, (user) => {
                        if (user) {
                            userId = user.uid;
                        } else {
                             userId = crypto.randomUUID(); // Fallback
                        }
                        isFirebaseReady = true;
                        
                        // Inicia a escuta do banco de dados após a autenticação
                        listenToData();
                    });

                } catch (error) {
                    console.error("Falha ao inicializar o Firebase:", error);
                    window.openModal(false, "Erro de Conexão", "Não foi possível inicializar o Firebase. Os dados em tempo real não estarão disponíveis.");
                }
            } else {
                console.error("Firebase SDK não carregado.");
            }
        };
        
        // --- FUNÇÃO DE ESCUTA DE DADOS (onSnapshot) ---
        function listenToData() {
            if (!db || !isFirebaseReady) return;

            // 1. Estatísticas de Impacto (GLOBAL)
            const statsDocRef = window.firebase.doc(db, `artifacts/${appId}/public/data/stats/global`);
            window.firebase.onSnapshot(statsDocRef, (docSnap) => {
                if (docSnap.exists()) {
                    updateImpactStats(docSnap.data());
                } else {
                     // Inicializa dados (simulação) se não existirem
                    initializeDefaultData(statsDocRef);
                }
            }, (error) => console.error("Erro ao buscar estatísticas:", error));

            // 2. Dados de Rastreamento (ROBÔS)
            const robotsCollectionRef = window.firebase.collection(db, `artifacts/${appId}/public/data/robots`);
            const robotsQuery = window.firebase.query(robotsCollectionRef);
            window.firebase.onSnapshot(robotsQuery, (snapshot) => {
                const robotsData = snapshot.docs.map(doc => ({
                    id: doc.id,
                    ...doc.data()
                }));
                updateTrackingPanel(robotsData);
            }, (error) => console.error("Erro ao buscar robôs:", error));
        }

        async function initializeDefaultData(statsDocRef) {
             try {
                 await window.firebase.setDoc(statsDocRef, {
                    lixoColetado: 45230,
                    kmLimpos: 1847.5,
                    robosAtivos: 8,
                    pesquisasAtivas: 127
                 });
                 // Cria robôs iniciais
                 await window.firebase.setDoc(window.firebase.doc(db, `artifacts/${appId}/public/data/robots`, 'robo_1'), { nome: 'Guardião I', lat: 50, lng: 35, status: 'operacao', kmLimpos: 234.5, lixoColetado: 1450, patrocinador: 'Petrobras' });
                 await window.firebase.setDoc(window.firebase.doc(db, `artifacts/${appId}/public/data/robots`, 'robo_2'), { nome: 'Protetor II', lat: 45, lng: 60, status: 'carregando', kmLimpos: 198.2, lixoColetado: 1120, patrocinador: 'Vale' });
             } catch (error) {
                 console.warn("Aviso: Falha na inicialização de dados (provavelmente erro de permissão ou timing).", error);
             }
        }

        // --- RENDERIZAÇÃO DE DADOS ---

        function updateImpactStats(stats) {
            const container = document.getElementById('impact-stats-container');
            container.innerHTML = `
                ${createStatCard(stats.lixoColetado, 'KG Lixo Coletado', 'droplet')}
                ${createStatCard(stats.kmLimpos.toFixed(1), 'KM Costa Limpa', 'waves')}
                ${createStatCard(stats.robosAtivos, 'Robôs Ativos Agora', 'anchor')}
                ${createStatCard(stats.pesquisasAtivas, 'Pesquisas em Curso', 'bar-chart-3')}
            `;
            // Re-renderiza ícones
            window.lucide.createIcons({ icons: window.lucide.icons });
        }

        function createStatCard(value, label, iconName) {
            return `
                <div class="bg-white p-10 text-center border-b-4 btn-flat" style="border-color: var(--accent-cyan);">
                    <i data-lucide="${iconName}" class="w-14 h-14 mx-auto mb-6" style="color: var(--accent-cyan);" stroke-width="1.5"></i>
                    <div class="text-5xl font-bold mb-3" style="color: var(--primary-navy);">
                        ${value.toLocaleString('pt-BR')}
                    </div>
                    <div class="text-sm text-gray-600 uppercase tracking-widest font-semibold">${label}</div>
                </div>
            `;
        }

        function updateTrackingPanel(robots) {
            const mapContainer = document.getElementById('map-container');
            const panel = document.getElementById('robots-panel');
            panel.innerHTML = ''; // Limpa o painel
            
            // Limpa e recria a grade do mapa (simulação)
            const grid = document.getElementById('map-grid');
            if(grid) {
                grid.innerHTML = '';
                for (let i = 0; i < 15; i++) {
                    grid.innerHTML += `<div class="absolute w-full border-t opacity-10" style="top: ${((i + 1) * 6.67)}%; border-color: var(--accent-cyan);"></div>`;
                    grid.innerHTML += `<div class="absolute h-full border-l opacity-10" style="left: ${((i + 1) * 6.67)}%; border-color: var(--accent-cyan);"></div>`;
                }
            }


            robots.forEach(robot => {
                // Adiciona o marcador ao mapa
                const marker = document.createElement('button');
                marker.className = "absolute transform -translate-x-1/2 -translate-y-1/2 w-10 h-10 hover:opacity-80 transition-all hover:scale-110 flex items-center justify-center group z-10 btn-flat";
                marker.style.left = `${robot.lng}%`; 
                marker.style.top = `${robot.lat}%`;
                marker.style.backgroundColor = 'var(--accent-cyan)';
                marker.onclick = () => selectRobot(robot.id);

                marker.innerHTML = `
                    <i data-lucide="anchor" class="w-6 h-6 text-white"></i>
                    <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs font-bold whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity btn-flat" style="background-color: var(--accent-cyan); color: var(--neutral-white);">
                        ${robot.nome}
                    </div>
                `;
                mapContainer.appendChild(marker);

                // Adiciona o card ao painel lateral
                const statusClass = robot.status === 'operacao' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                const card = document.createElement('button');
                card.id = `robot-card-${robot.id}`;
                card.className = `w-full bg-white p-5 text-left transition-all hover:shadow-lg hover:ring-4 hover:ring-accent-cyan/50 btn-flat`;
                card.innerHTML = `
                    <div class="flex items-start justify-between mb-3">
                        <div class="font-bold text-lg" style="color: var(--primary-navy);">${robot.nome}</div>
                        <span class="text-xs px-3 py-1 font-bold btn-flat ${statusClass}">${robot.status === 'operacao' ? 'EM OPERAÇÃO' : 'CARREGANDO'}</span>
                    </div>
                    <div class="space-y-2 text-sm text-gray-700">
                        <div class="flex justify-between"><span>KM Limpos:</span><span class="font-bold" style="color: var(--primary-navy);">${robot.kmLimpos.toFixed(1)} km</span></div>
                        <div class="flex justify-between"><span>Lixo Coletado:</span><span class="font-bold" style="color: var(--primary-navy);">${robot.lixoColetado} kg</span></div>
                        ${robot.patrocinador ? `<div class="flex justify-between pt-2 border-t border-gray-200"><span>Patrocinador:</span><span class="font-bold" style="color: var(--accent-cyan);">${robot.patrocinador}</span></div>` : ''}
                    </div>
                `;
                card.onclick = () => selectRobot(robot.id);
                panel.appendChild(card);
            });
            window.lucide.createIcons({ icons: window.lucide.icons });
        }

        // --- INTERAÇÃO DO USUÁRIO ---
        
        // Menu Mobile
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
        document.getElementById('menu-button').addEventListener('click', toggleMenu);


        // Simulação de Seleção de Robô
        let selectedRobotId = null;
        function selectRobot(id) {
            selectedRobotId = id;
            document.querySelectorAll('#robots-panel button').forEach(btn => {
                btn.classList.remove('ring-4');
                btn.style.boxShadow = 'none';
            });
            const selectedButton = document.getElementById(`robot-card-${id}`);
            if (selectedButton) {
                selectedButton.classList.add('ring-4');
                selectedButton.style.boxShadow = `0 0 0 4px var(--accent-cyan)`;
            }
        }


        // --- FUNÇÕES DA API GEMINI ---

        const fetchWithExponentialBackoff = async (url, options) => {
            for (let i = 0; i < MAX_RETRIES; i++) {
                try {
                    const response = await fetch(url, options);
                    if (!response.ok && response.status === 429) throw new Error('Rate limit exceeded');
                    if (!response.ok) {
                        const errorBody = await response.text();
                        throw new Error(`HTTP error! status: ${response.status}. Body: ${errorBody.substring(0, 100)}...`);
                    }
                    return response;
                } catch (error) {
                    if (i === MAX_RETRIES - 1) throw error;
                    const delay = INITIAL_DELAY * Math.pow(2, i) + Math.random() * 1000;
                    await new Promise(resolve => setTimeout(resolve, delay));
                }
            }
        };

        async function generateProposalDraft() {
            const empresa = document.getElementById('empresa').value.trim();
            const llmInput = document.getElementById('llm-input').value.trim();
            const mensagemInput = document.getElementById('mensagem');
            const loadingState = document.getElementById('llm-loading-state');
            const citationsDiv = document.getElementById('llm-citations');
            const citationsList = document.getElementById('citations-list');
            const btn = document.getElementById('generate-draft-btn');

            if (!empresa || !llmInput) {
                window.openModal(false, "Entrada Inválida", "Por favor, preencha o nome da empresa e os focos ESG antes de gerar o rascunho.");
                return;
            }

            btn.disabled = true;
            loadingState.classList.remove('hidden');
            citationsDiv.classList.add('hidden');
            citationsList.innerHTML = '';
            mensagemInput.value = "Gerando rascunho... (Usando IA e pesquisa em tempo real) Por favor, aguarde.";


            try {
                const systemPrompt = `Aja como um especialista em Sustentabilidade e ESG. Sua tarefa é criar um parágrafo conciso e impactante (máximo 150 palavras) que sirva como rascunho de uma mensagem de patrocínio corporativo. O rascunho deve conectar os objetivos de sustentabilidade da empresa (${empresa}) com a missão do Instituto Maré Futuro (robótica autônoma para limpeza de oceanos e geração de dados científicos). Use os seguintes focos chave fornecidos pelo usuário: "${llmInput}". O tom deve ser profissional e focado no retorno de investimento e ESG.`;
                const userQuery = `Gerar rascunho de proposta de patrocínio para a empresa ${empresa} com foco em: ${llmInput}. Use dados e tendências atuais sobre poluição marinha e ESG, se possível.`;

                const payload = {
                    contents: [{ parts: [{ text: userQuery }] }],
                    tools: [{ "google_search": {} }],
                    systemInstruction: { parts: [{ text: systemPrompt }] },
                };

                const response = await fetchWithExponentialBackoff(API_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();
                const candidate = result.candidates?.[0];
                const generatedText = candidate?.content?.parts?.[0]?.text || "Falha ao gerar texto. Tente novamente.";
                mensagemInput.value = generatedText;

                // Extrair fontes
                const groundingMetadata = candidate?.groundingMetadata;
                if (groundingMetadata && groundingMetadata.groundingAttributions) {
                    let sourceList = groundingMetadata.groundingAttributions
                        .map(attribution => ({
                            uri: attribution.web?.uri,
                            title: attribution.web?.title,
                        }))
                        .filter(source => source.uri && source.title);
                    
                    if (sourceList.length > 0) {
                        sourceList.forEach(source => {
                            citationsList.innerHTML += `<li><a href="${source.uri}" target="_blank" rel="noopener noreferrer" class="hover:text-accent-cyan underline">${source.title}</a></li>`;
                        });
                        citationsDiv.classList.remove('hidden');
                    }
                }

            } catch (error) {
                console.error("Gemini API Error:", error);
                mensagemInput.value = "Desculpe, houve um erro na geração de IA. Tente reduzir a complexidade do pedido.";
                window.openModal(false, "Erro de Geração de IA", "Não foi possível gerar o rascunho da proposta.");
            } finally {
                btn.disabled = false;
                loadingState.classList.add('hidden');
            }
        }
        document.getElementById('generate-draft-btn').addEventListener('click', generateProposalDraft);


        // --- SUBMISSÃO DO FORMULÁRIO (Firestore) ---
        document.getElementById('sponsorship-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!db || !userId) {
                window.openModal(false, "Erro de Conexão", "Sistema de dados indisponível. Tente novamente mais tarde.");
                return;
            }

            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'ENVIANDO...';

            const formData = {
                nome: document.getElementById('nome').value,
                cargo: document.getElementById('cargo').value,
                empresa: document.getElementById('empresa').value,
                email: document.getElementById('email').value,
                telefone: document.getElementById('telefone').value,
                orcamento: document.getElementById('orcamento').value,
                mensagem: document.getElementById('mensagem').value,
            };

            try {
                await window.firebase.addDoc(window.firebase.collection(db, `artifacts/${appId}/public/data/sponsorship_requests`), {
                    ...formData,
                    userId: userId,
                    timestamp: new Date().toISOString()
                });

                window.openModal(true, "Proposta Enviada com Sucesso!", "Agradecemos o seu interesse. Nossa equipe entrará em contato em até 48 horas úteis.");
                this.reset(); // Limpa o formulário
                
            } catch (error) {
                console.error("Erro ao enviar proposta:", error);
                window.openModal(false, "Erro ao Enviar Proposta", "Houve um erro no sistema. Por favor, tente novamente ou entre em contato direto.");
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'ENVIAR PROPOSTA <i data-lucide="chevron-right" class="w-5 h-5"></i>';
                window.lucide.createIcons({ icons: window.lucide.icons });
            }
        });

    </script>
</body>
</html>
