import React, { useState, useEffect, useCallback } from 'react';
import { Menu, X, Droplet, Waves, Anchor, BarChart3, MapPin, ChevronRight, Download, ExternalLink, Check, AlertTriangle, Sparkles } from 'lucide-react';

// FIREBASE IMPORTS (Requeridos para o Canvas)
import { initializeApp } from 'firebase/app';
import { getAuth, signInAnonymously, signInWithCustomToken, onAuthStateChanged } from 'firebase/auth';
import { getFirestore, doc, getDoc, addDoc, setDoc, updateDoc, deleteDoc, onSnapshot, collection, query, where } from 'firebase/firestore';

// --- Variáveis de Estilo (Mantendo o design Minimalista e Flat) ---
const COLORS = {
  primaryNavy: '#00204A',
  accentCyan: '#00A896',
  neutralWhite: '#FFFFFF',
  neutralBlack: '#000000',
};

// --- Funções de Utilitário para API Gemini ---
const MAX_RETRIES = 5;
const INITIAL_DELAY = 1000;
const API_KEY = ""; // Chave de API é fornecida pelo ambiente
const API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-05-20:generateContent?key=${API_KEY}`;

/**
 * Função para fazer fetch com lógica de backoff exponencial em caso de falha.
 */
const fetchWithExponentialBackoff = async (url, options) => {
    for (let i = 0; i < MAX_RETRIES; i++) {
        try {
            const response = await fetch(url, options);
            if (!response.ok && response.status === 429) {
                throw new Error('Rate limit exceeded');
            }
            if (!response.ok) {
                // Tenta ler a resposta para obter detalhes do erro, se disponível
                const errorBody = await response.text();
                throw new Error(`HTTP error! status: ${response.status}. Body: ${errorBody.substring(0, 100)}...`);
            }
            return response;
        } catch (error) {
            if (i === MAX_RETRIES - 1) throw error; // Re-throw on last attempt
            const delay = INITIAL_DELAY * Math.pow(2, i) + Math.random() * 1000;
            // console.log(`Retry ${i + 1}/${MAX_RETRIES} after ${delay.toFixed(0)}ms due to error: ${error.message}`);
            await new Promise(resolve => setTimeout(resolve, delay));
        }
    }
};

// --- Custom Hook para Lógica de Dados (Firestore) ---
const useMareFuturoData = (db, isFirebaseReady) => {
  const [isLoading, setIsLoading] = useState(true);
  const [stats, setStats] = useState({
    lixoColetado: 0,
    kmLimpos: 0,
    robosAtivos: 0,
    pesquisasAtivas: 0
  });
  const [robots, setRobots] = useState([]);
  const appId = typeof __app_id !== 'undefined' ? __app_id : 'default-app-id';

  useEffect(() => {
    if (!isFirebaseReady || !db) return;

    // --- 1. Busca de Estatísticas de Impacto (Coleção: stats) ---
    const statsDocRef = doc(db, `artifacts/${appId}/public/data/stats/global`);
    
    // Tenta inicializar os dados se o documento não existir (apenas para simulação inicial)
    const initializeData = async () => {
        try {
            const docSnap = await getDoc(statsDocRef);
            if (!docSnap.exists()) {
                 await setDoc(statsDocRef, {
                    lixoColetado: 45230,
                    kmLimpos: 1847.5,
                    robosAtivos: 8,
                    pesquisasAtivas: 127
                 });
                 // Cria robôs iniciais
                 await setDoc(doc(db, `artifacts/${appId}/public/data/robots`, 'robo_1'), { nome: 'Guardião I', lat: 50, lng: 35, status: 'operacao', kmLimpos: 234.5, lixoColetado: 1450, patrocinador: 'Petrobras' });
                 await setDoc(doc(db, `artifacts/${appId}/public/data/robots`, 'robo_2'), { nome: 'Protetor II', lat: 45, lng: 60, status: 'carregando', kmLimpos: 198.2, lixoColetado: 1120, patrocinador: 'Vale' });
                 await setDoc(doc(db, `artifacts/${appId}/public/data/robots`, 'robo_3'), { nome: 'Onda Azul III', lat: 70, lng: 45, status: 'operacao', kmLimpos: 312.8, lixoColetado: 1890, patrocinador: null });
            }
        } catch (error) {
            // Captura silenciosamente erros de permissão/timing durante a inicialização assíncrona.
            console.warn("Aviso: Falha na inicialização de dados (provavelmente erro de permissão ou dados já existem). onSnapshot tentará a leitura.", error);
        }
    };
    initializeData();

    const unsubscribeStats = onSnapshot(statsDocRef, (docSnap) => {
        if (docSnap.exists()) {
            const data = docSnap.data();
            setStats({
                lixoColetado: data.lixoColetado || 0,
                kmLimpos: data.kmLimpos ? data.kmLimpos * 1 : 0, // Garante que é um número
                robosAtivos: data.robosAtivos || 0,
                pesquisasAtivas: data.pesquisasAtivas || 0,
            });
        }
        setIsLoading(false);
    }, (error) => console.error("Erro ao buscar estatísticas:", error)); // Callback de erro de onSnapshot

    // --- 2. Busca de Dados de Rastreamento (Coleção: robots) ---
    const robotsQuery = query(collection(db, `artifacts/${appId}/public/data/robots`));
    const unsubscribeRobots = onSnapshot(robotsQuery, (snapshot) => {
      const robotsData = snapshot.docs.map(doc => ({
        id: doc.id,
        ...doc.data()
      }));
      setRobots(robotsData);
    }, (error) => console.error("Erro ao buscar robôs:", error)); // Callback de erro de onSnapshot

    return () => {
      unsubscribeStats();
      unsubscribeRobots();
    };
  }, [db, isFirebaseReady, appId]);

  return { isLoading, stats, robots };
};


// --- Componentes Reutilizáveis (Simulando Módulos) ---

// Componente de Modal (Bloco)
const SimpleModal = ({ children, title, isOpen, onClose, isSuccess }) => {
  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 bg-primary-navy bg-opacity-90 flex items-center justify-center z-[1000]">
      <div className="p-8 max-w-lg w-full" style={{ backgroundColor: COLORS.neutralWhite, color: COLORS.neutralBlack }}>
        <div className="flex justify-between items-center mb-6 border-b pb-4" style={{ borderColor: COLORS.accentCyan }}>
          <div className="flex items-center">
            {isSuccess ? 
                <Check size={28} className="mr-3" style={{ color: COLORS.accentCyan }}/> : 
                <AlertTriangle size={28} className="mr-3 text-red-600"/>
            }
            <h3 className="text-2xl font-bold" style={{ color: COLORS.primaryNavy }}>{title}</h3>
          </div>
          <button onClick={onClose} className="p-2 hover:bg-neutral-100 transition-colors">
            <X size={24} />
          </button>
        </div>
        <div>{children}</div>
      </div>
    </div>
  );
};


// 1. Componente de Cartão de Estatística
const StatCard = ({ value, label, icon: Icon, suffix = '' }) => (
  <div className="bg-white p-10 text-center border-b-4" style={{ borderColor: COLORS.accentCyan }}>
    <Icon className="w-14 h-14 mx-auto mb-6" style={{ color: COLORS.accentCyan }} strokeWidth={1.5} />
    <div className="text-5xl font-bold mb-3" style={{ color: COLORS.primaryNavy }}>
      {value.toLocaleString('pt-BR')}{suffix}
    </div>
    <div className="text-sm text-neutral-600 uppercase tracking-widest font-semibold">{label}</div>
  </div>
);

// 2. Componente de Cabeçalho (Simulando Header.jsx)
const Header = ({ menuOpen, setMenuOpen }) => (
  <header className="sticky top-0 z-50 border-b-2" style={{ backgroundColor: COLORS.primaryNavy, color: COLORS.neutralWhite, borderColor: COLORS.accentCyan }}>
    <div className="container mx-auto px-6">
      <div className="flex items-center justify-between h-20">
        <div className="flex items-center gap-3">
          <Waves className="w-8 h-8" style={{ color: COLORS.accentCyan }} />
          <div className="text-xl font-bold tracking-wider">INSTITUTO MARÉ FUTURO</div>
        </div>
        
        {/* Navegação Desktop */}
        <nav className="hidden lg:flex items-center gap-10">
          {['HOME', 'IMPACTO', 'RASTREAMENTO', 'PESQUISA', 'PATROCÍNIO', 'SOBRE'].map(item => (
            <a key={item} href={`#${item.toLowerCase()}`} className="text-sm font-semibold tracking-wide hover:text-accent-cyan transition-colors" style={{ color: COLORS.neutralWhite }}>{item}</a>
          ))}
        </nav>

        {/* Menu Mobile */}
        <button 
          className="lg:hidden"
          onClick={() => setMenuOpen(!menuOpen)}
          aria-label="Menu"
        >
          {menuOpen ? <X size={28} /> : <Menu size={28} />}
        </button>
      </div>

      {/* Menu Aberto Mobile */}
      {menuOpen && (
        <nav className="lg:hidden pb-6 space-y-4 border-t pt-6 mt-2" style={{ borderColor: COLORS.accentCyan }}>
          {['HOME', 'IMPACTO', 'RASTREAMENTO', 'PESQUISA', 'PATROCÍNIO', 'SOBRE'].map(item => (
            <a key={item} href={`#${item.toLowerCase()}`} className="block text-base font-semibold tracking-wide" onClick={() => setMenuOpen(false)}>{item}</a>
          ))}
        </nav>
      )}
    </div>
  </header>
);

// 3. Componente de Seção Principal (Hero)
const HeroSection = () => (
  <section id="home" className="py-32" style={{ backgroundColor: COLORS.primaryNavy, color: COLORS.neutralWhite }}>
    <div className="container mx-auto px-6">
      <div className="max-w-4xl">
        <div className="w-20 h-1 mb-8" style={{ backgroundColor: COLORS.accentCyan }}></div>
        <h1 className="text-6xl lg:text-7xl font-bold mb-8 leading-tight">
          Robótica Autônoma<br />Protegendo Oceanos
        </h1>
        <p className="text-xl lg:text-2xl mb-12 leading-relaxed text-neutral-300 max-w-2xl">
          Tecnologia de ponta trabalhando 24/7 para coletar lixo marinho e gerar dados científicos precisos sobre a poluição oceânica em tempo real.
        </p>
        <div className="flex flex-col sm:flex-row gap-4">
          <a href="#patrocinio" className="px-10 py-5 font-bold text-sm tracking-wider hover:opacity-90 transition-colors flex items-center justify-center" style={{ backgroundColor: COLORS.accentCyan, color: COLORS.neutralWhite }}>
            PATROCINE AGORA
          </a>
          <a href="#rastreamento" className="border-2 px-10 py-5 font-bold text-sm tracking-wider hover:bg-neutral-white hover:text-primary-navy transition-colors flex items-center justify-center" style={{ borderColor: COLORS.neutralWhite, color: COLORS.neutralWhite }}>
            VER ROBÔS AO VIVO
          </a>
        </div>
      </div>
    </div>
  </section>
);

// 4. Componente de Seção de Impacto
const ImpactSection = ({ isLoading, stats }) => (
  <section id="impacto" className="py-20 bg-neutral-white">
    <div className="container mx-auto px-6">
      <div className="text-center mb-16">
        <div className="w-20 h-1 mx-auto mb-6" style={{ backgroundColor: COLORS.accentCyan }}></div>
        <h2 className="text-5xl font-bold mb-4" style={{ color: COLORS.primaryNavy }}>Impacto em Tempo Real</h2>
        <p className="text-lg text-neutral-600 max-w-2xl mx-auto">
          Dados atualizados automaticamente via Firestore. Transparência total sobre nossa operação e resultados.
        </p>
      </div>

      {isLoading ? (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-pulse">
          {[...Array(4)].map((_, i) => (
            <div key={i} className="h-64 bg-neutral-200"></div>
          ))}
        </div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <StatCard value={stats.lixoColetado} label="KG Lixo Coletado" icon={Droplet} />
          <StatCard value={stats.kmLimpos.toFixed(1)} label="KM Costa Limpa" icon={Waves} />
          <StatCard value={stats.robosAtivos} label="Robôs Ativos Agora" icon={Anchor} />
          <StatCard value={stats.pesquisasAtivas} label="Pesquisas em Curso" icon={BarChart3} />
        </div>
      )}
    </div>
  </section>
);

// 5. Componente de Rastreamento (com Mapa Simulado)
const TrackingSection = ({ robots, selectedRobot, setSelectedRobot }) => (
  <section id="rastreamento" className="py-20 bg-neutral-100">
    <div className="container mx-auto px-6">
      <div className="text-center mb-16">
        <div className="w-20 h-1 mx-auto mb-6" style={{ backgroundColor: COLORS.accentCyan }}></div>
        <h2 className="text-5xl font-bold mb-4" style={{ color: COLORS.primaryNavy }}>Rastreamento ao Vivo</h2>
        <p className="text-lg text-neutral-600 max-w-2xl mx-auto">
          Acompanhe a localização exata de cada robô trabalhando agora. Transparência é nossa prioridade.
        </p>
      </div>

      <div className="grid lg:grid-cols-5 gap-8">
        {/* Mapa Simulado (Bloco Retangular Grande) */}
        <div className="lg:col-span-3 h-[500px] relative overflow-hidden" style={{ backgroundColor: COLORS.primaryNavy }}>
          {/* Simulação de Rede Geográfica no Mapa */}
          <div className="absolute inset-0">
            {[...Array(15)].map((_, i) => (
              <React.Fragment key={i}>
                <div className="absolute w-full border-t opacity-10" style={{ top: `${(i + 1) * 6.67}%`, borderColor: COLORS.accentCyan }} />
                <div className="absolute h-full border-l opacity-10" style={{ left: `${(i + 1) * 6.67}%`, borderColor: COLORS.accentCyan }} />
              </React.Fragment>
            ))}
          </div>

          {/* Marcadores de Robôs */}
          {robots.map((robot) => (
            <button
              key={robot.id}
              onClick={() => setSelectedRobot(robot)}
              className="absolute transform -translate-x-1/2 -translate-y-1/2 w-10 h-10 hover:opacity-80 transition-all hover:scale-110 flex items-center justify-center group z-10"
              style={{ 
                left: `${robot.lng}%`, 
                top: `${robot.lat}%`,
                backgroundColor: COLORS.accentCyan 
              }}
            >
              <Anchor className="w-6 h-6 text-white" />
              <div className="absolute -top-8 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs font-bold whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity" style={{ backgroundColor: COLORS.accentCyan, color: COLORS.neutralWhite }}>
                {robot.nome}
              </div>
            </button>
          ))}

          {/* Indicador Live */}
          <div className="absolute top-6 right-6 px-4 py-2 text-xs font-bold flex items-center gap-2 z-20" style={{ backgroundColor: COLORS.accentCyan, color: COLORS.neutralWhite }}>
            <div className="w-2 h-2 bg-white animate-pulse"></div>
            TRANSMISSÃO AO VIVO
          </div>
        </div>

        {/* Painel de Detalhes dos Robôs */}
        <div className="lg:col-span-2 space-y-4">
          {robots.map((robot) => (
            <button
              key={robot.id}
              onClick={() => setSelectedRobot(robot)}
              className={`w-full bg-white p-5 text-left transition-all hover:shadow-lg hover:ring-4 hover:ring-accent-cyan/50 ${
                selectedRobot?.id === robot.id ? 'ring-4' : ''
              }`}
              style={{ 
                borderColor: COLORS.accentCyan,
                boxShadow: selectedRobot?.id === robot.id ? `0 0 0 4px ${COLORS.accentCyan}` : 'none'
              }}
            >
              <div className="flex items-start justify-between mb-3">
                <div className="font-bold text-lg" style={{ color: COLORS.primaryNavy }}>{robot.nome}</div>
                <span className={`text-xs px-3 py-1 font-bold ${
                  robot.status === 'operacao' 
                    ? 'bg-green-100 text-green-800' 
                    : 'bg-yellow-100 text-yellow-800'
                }`}>
                  {robot.status === 'operacao' ? 'EM OPERAÇÃO' : 'CARREGANDO'}
                </span>
              </div>
              
              <div className="space-y-2 text-sm text-neutral-700">
                <div className="flex justify-between">
                  <span>KM Limpos:</span>
                  <span className="font-bold" style={{ color: COLORS.primaryNavy }}>{robot.kmLimpos.toFixed(1)} km</span>
                </div>
                <div className="flex justify-between">
                  <span>Lixo Coletado:</span>
                  <span className="font-bold" style={{ color: COLORS.primaryNavy }}>{robot.lixoColetado} kg</span>
                </div>
                {robot.patrocinador && (
                  <div className="flex justify-between pt-2 border-t border-neutral-200">
                    <span>Patrocinador:</span>
                    <span className="font-bold" style={{ color: COLORS.accentCyan }}>{robot.patrocinador}</span>
                  </div>
                )}
              </div>
            </button>
          ))}
        </div>
      </div>
    </div>
  </section>
);

// 6. Componente de Pesquisa e Dados
const ResearchSection = () => (
  <section id="pesquisa" className="py-20 bg-white">
    <div className="container mx-auto px-6">
      <div className="max-w-5xl mx-auto">
        <div className="w-20 h-1 mb-6" style={{ backgroundColor: COLORS.accentCyan }}></div>
        <h2 className="text-5xl font-bold mb-6" style={{ color: COLORS.primaryNavy }}>Dados Científicos Abertos</h2>
        <p className="text-lg text-neutral-600 mb-12 max-w-3xl">
          Parceria com universidades e centros de pesquisa. Nossa robótica gera dados georreferenciados cruciais para estudos de microplásticos e poluição.
        </p>

        <div className="grid md:grid-cols-2 gap-8">
          <div className="bg-neutral-100 p-8">
            <BarChart3 className="w-12 h-12 mb-6" style={{ color: COLORS.accentCyan }} />
            <h3 className="text-2xl font-bold mb-4" style={{ color: COLORS.primaryNavy }}>API de Pesquisa (Premium)</h3>
            <p className="text-neutral-700 mb-6">
              Endpoint <code className="bg-white px-2 py-1 text-sm text-primary-navy">/api/research/data</code> com autenticação tokenizada para acesso a dados brutos e históricos.
            </p>
            <button className="px-6 py-3 font-bold text-sm flex items-center gap-2 hover:opacity-90 transition-colors" style={{ backgroundColor: COLORS.accentCyan, color: COLORS.neutralWhite }}>
              DOCUMENTAÇÃO API
              <ExternalLink size={16} />
            </button>
          </div>

          <div className="bg-neutral-100 p-8">
            <Download className="w-12 h-12 mb-6" style={{ color: COLORS.accentCyan }} />
            <h3 className="text-2xl font-bold mb-4" style={{ color: COLORS.primaryNavy }}>Relatórios Públicos</h3>
            <p className="text-neutral-700 mb-6">
              Relatórios anuais de atividades e prestação de contas (ESG). Transparência total para apoios e financiamentos.
            </p>
            <button className="px-6 py-3 font-bold text-sm flex items-center gap-2 hover:opacity-90 transition-colors" style={{ backgroundColor: COLORS.accentCyan, color: COLORS.neutralWhite }}>
              BAIXAR RELATÓRIOS
              <Download size={16} />
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
);

// 7. Componente de Formulário de Patrocínio
const SponsorshipForm = ({ db, userId, openModal }) => {
  const [formData, setFormData] = useState({
    nome: '', cargo: '', empresa: '', email: '', telefone: '', orcamento: '', mensagem: ''
  });
  const [isSubmitted, setIsSubmitted] = useState(false);
  const [isLlmLoading, setIsLlmLoading] = useState(false);
  const [llmInput, setLlmInput] = useState(''); // Novo estado para input da IA
  const [citations, setCitations] = useState([]); // NOVO: Estado para armazenar as fontes/citações

  const handleFormChange = (field, value) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  // Função para gerar rascunho de proposta usando a API Gemini
  const generateProposalDraft = useCallback(async () => {
    if (formData.empresa.trim() === '' || llmInput.trim() === '') {
        openModal(false, "Entrada Inválida", "Por favor, preencha o nome da empresa e os focos ESG antes de gerar o rascunho.");
        return;
    }
    
    setIsLlmLoading(true);
    setFormData(prev => ({ ...prev, mensagem: "Gerando rascunho... (Usando IA e pesquisa em tempo real) Por favor, aguarde." }));
    setCitations([]); // Limpa citações anteriores

    try {
        const systemPrompt = `Aja como um especialista em Sustentabilidade e ESG. Sua tarefa é criar um parágrafo conciso e impactante (máximo 150 palavras) que sirva como rascunho de uma mensagem de patrocínio corporativo. O rascunho deve conectar os objetivos de sustentabilidade da empresa (${formData.empresa}) com a missão do Instituto Maré Futuro (robótica autônoma para limpeza de oceanos e geração de dados científicos). Use os seguintes focos chave fornecidos pelo usuário: "${llmInput}". O tom deve ser profissional e focado no retorno de investimento e ESG.`;
        const userQuery = `Gerar rascunho de proposta de patrocínio para a empresa ${formData.empresa} com foco em: ${llmInput}. Use dados e tendências atuais sobre poluição marinha e ESG, se possível.`;

        const payload = {
            contents: [{ parts: [{ text: userQuery }] }],
            tools: [{ "google_search": {} }], // Usando Google Search Grounding
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
        
        // NOVO: Extrair fontes de grounding
        let sourceList = [];
        const groundingMetadata = candidate?.groundingMetadata;
        if (groundingMetadata && groundingMetadata.groundingAttributions) {
            sourceList = groundingMetadata.groundingAttributions
                .map(attribution => ({
                    uri: attribution.web?.uri,
                    title: attribution.web?.title,
                }))
                .filter(source => source.uri && source.title);
        }
        setCitations(sourceList);
        
        // Atualiza o campo de mensagem com o texto gerado
        setFormData(prev => ({ ...prev, mensagem: generatedText }));

    } catch (error) {
        console.error("Gemini API Error:", error);
        setFormData(prev => ({ ...prev, mensagem: "Desculpe, houve um erro na geração de IA. Tente reduzir a complexidade do pedido." }));
        openModal(false, "Erro de Geração de IA", "Não foi possível gerar o rascunho da proposta. Verifique o console para detalhes.");
    } finally {
        setIsLlmLoading(false);
    }
  }, [formData.empresa, llmInput, openModal]);

  // Função para salvar a proposta no Firestore
  const handleFormSubmit = async (e) => {
    e.preventDefault();
    
    if (!db || !userId) {
        openModal(false, "Erro de Conexão", "Não foi possível conectar ao sistema de banco de dados. Tente novamente mais tarde.");
        return;
    }

    setIsSubmitted(true);
    
    const appId = typeof __app_id !== 'undefined' ? __app_id : 'default-app-id';

    try {
        await addDoc(collection(db, `artifacts/${appId}/public/data/sponsorship_requests`), {
            ...formData,
            userId: userId,
            timestamp: new Date().toISOString()
        });

        openModal(true, "Proposta Enviada com Sucesso!", "Agradecemos o seu interesse. Nossa equipe de Parcerias entrará em contato em até 48 horas úteis para discutir a sua contribuição.");
        setFormData({ nome: '', cargo: '', empresa: '', email: '', telefone: '', orcamento: '', mensagem: '' });
        
    } catch (error) {
        console.error("Erro ao enviar proposta:", error);
        openModal(false, "Erro ao Enviar Proposta", "Houve um erro no sistema. Por favor, tente novamente ou entre em contato direto.");
    } finally {
        setIsSubmitted(false);
    }
  };

  const InputField = ({ type = 'text', placeholder, field, required = true, isFullWidth = false, disabled = false }) => (
    <input
      type={type}
      placeholder={placeholder}
      value={formData[field]}
      onChange={(e) => handleFormChange(field, e.target.value)}
      className={`px-5 py-4 border-2 border-neutral-300 focus:border-accent-cyan outline-none text-base ${isFullWidth ? 'w-full' : ''}`}
      required={required}
      disabled={isSubmitted || disabled}
    />
  );
  
  return (
    <section id="patrocinio" className="py-20" style={{ backgroundColor: COLORS.primaryNavy, color: COLORS.neutralWhite }}>
      <div className="container mx-auto px-6">
        <div className="max-w-4xl mx-auto">
          <div className="text-center mb-12">
            <div className="w-20 h-1 mx-auto mb-6" style={{ backgroundColor: COLORS.accentCyan }}></div>
            <h2 className="text-5xl font-bold mb-4">Patrocínio Corporativo</h2>
            <p className="text-xl text-neutral-300 max-w-2xl mx-auto">
              Empresas comprometidas com ESG e sustentabilidade. Associe sua marca à inovação e impacto ambiental real.
            </p>
          </div>

          <form onSubmit={handleFormSubmit} className="bg-white text-neutral-black p-10">
            <div className="space-y-6">
              
              <div className="grid md:grid-cols-2 gap-6">
                <InputField placeholder="Nome Completo *" field="nome" />
                <InputField placeholder="Cargo *" field="cargo" />
              </div>

              <InputField placeholder="Empresa *" field="empresa" isFullWidth />

              <div className="grid md:grid-cols-2 gap-6">
                <InputField type="email" placeholder="E-mail Corporativo *" field="email" />
                <InputField type="tel" placeholder="Telefone *" field="telefone" />
              </div>

              <select
                value={formData.orcamento}
                onChange={(e) => handleFormChange('orcamento', e.target.value)}
                className="w-full px-5 py-4 border-2 border-neutral-300 focus:border-accent-cyan outline-none text-base"
                required
                disabled={isSubmitted}
              >
                <option value="" disabled>Orçamento Estimado *</option>
                <option value="10k-50k">R$ 10.000 - R$ 50.000</option>
                <option value="50k-100k">R$ 50.000 - R$ 100.000</option>
                <option value="100k-500k">R$ 100.000 - R$ 500.000</option>
                <option value="500k+">Acima de R$ 500.000</option>
              </select>
              
              {/* --- BLOCO DE IA PARA GERAÇÃO DE MENSAGEM (EXPANDIDO) --- */}
              <div className='border-2 border-neutral-200 p-4'>
                <p className='text-sm font-bold mb-3' style={{ color: COLORS.primaryNavy }}>✨ Ferramenta de Rascunho ESG</p>
                <div className="flex gap-3">
                    <input
                      type="text"
                      placeholder="Focos ESG da Empresa (Ex: Inovação, Biodiversidade, Reciclagem) *"
                      value={llmInput}
                      onChange={(e) => setLlmInput(e.target.value)}
                      className="w-full px-4 py-3 border-2 border-neutral-300 focus:border-accent-cyan outline-none text-base"
                      disabled={isLlmLoading || isSubmitted}
                    />
                    <button
                        type="button"
                        onClick={generateProposalDraft}
                        className={`px-4 py-3 font-bold text-sm tracking-wider flex items-center justify-center transition-colors whitespace-nowrap ${isLlmLoading ? 'bg-gray-400 opacity-60' : 'bg-accent-cyan hover:opacity-80'}`}
                        style={{ backgroundColor: COLORS.accentCyan, color: COLORS.neutralWhite }}
                        disabled={isLlmLoading || isSubmitted}
                    >
                        {isLlmLoading ? 'IA PENSANDO...' : '✨ GERAR RASCUNHO'}
                    </button>
                </div>
                
                {/* NOVO: Exibição do estado de carregamento/fontes */}
                {isLlmLoading && (
                    <div className='mt-4 text-center py-2 text-sm font-semibold' style={{color: COLORS.accentCyan}}>
                        Gerando conteúdo... Pode levar até 10 segundos.
                    </div>
                )}

                {citations.length > 0 && !isLlmLoading && (
                    <div className='mt-4 pt-3 border-t border-neutral-300'>
                        <p className='text-xs font-bold text-primary-navy mb-1'>Fontes (Pesquisa Google para ESG):</p>
                        <ul className='text-xs text-neutral-700 list-disc list-inside space-y-1'>
                            {citations.map((source, index) => (
                                <li key={index}><a href={source.uri} target="_blank" rel="noopener noreferrer" className="hover:text-accent-cyan underline">{source.title}</a></li>
                            ))}
                        </ul>
                    </div>
                )}
              </div>
              {/* --------------------------------------------------- */}

              <textarea
                placeholder="Mensagem: O que sua empresa busca patrocinar? *"
                rows="5"
                value={formData.mensagem}
                onChange={(e) => handleFormChange('mensagem', e.target.value)}
                className="w-full px-5 py-4 border-2 border-neutral-300 focus:border-accent-cyan outline-none resize-none text-base"
                required
                disabled={isSubmitted || isLlmLoading}
              ></textarea>

              <button
                type="submit"
                className="w-full px-10 py-5 font-bold text-sm tracking-wider hover:opacity-90 transition-colors flex items-center justify-center gap-3"
                style={{ backgroundColor: COLORS.accentCyan, color: COLORS.neutralWhite }}
                disabled={isSubmitted || isLlmLoading}
              >
                {isSubmitted ? 'ENVIANDO...' : 'ENVIAR PROPOSTA'}
                <ChevronRight size={20} />
              </button>

              <p className="text-xs text-neutral-300 text-center">
                Ao enviar, você concorda com nossa Política de Privacidade.
              </p>
            </div>
          </form>
        </div>
      </div>
    </section>
  );
};

// 8. Componente de Rodapé (Simulando Footer.jsx)
const Footer = () => (
  <footer className="py-16" style={{ backgroundColor: COLORS.neutralBlack, color: COLORS.neutralWhite }}>
    <div className="container mx-auto px-6">
      <div className="grid md:grid-cols-4 gap-12 mb-12">
        <div>
          <div className="flex items-center gap-2 mb-6">
            <Waves className="w-8 h-8" style={{ color: COLORS.accentCyan }} />
            <div className="font-bold text-lg">MARÉ FUTURO</div>
          </div>
          <p className="text-neutral-400 text-sm leading-relaxed">
            Robótica e ciência trabalhando pela preservação dos oceanos.
          </p>
        </div>
        
        <div>
          <div className="font-bold mb-4 text-sm tracking-wider">NAVEGAÇÃO</div>
          <div className="space-y-3 text-sm text-neutral-400">
            {['Home', 'Impacto', 'Rastreamento', 'Pesquisa', 'Patrocínio'].map(item => (
              <div key={item}><a href={`#${item.toLowerCase()}`} className="hover:text-accent-cyan transition-colors">{item}</a></div>
            ))}
          </div>
        </div>

        <div>
          <div className="font-bold mb-4 text-sm tracking-wider">TRANSPARÊNCIA</div>
          <div className="space-y-3 text-sm text-neutral-400">
            {['Relatórios Anuais', 'Prestação de Contas', 'Estatuto', 'Governança'].map(item => (
              <div key={item}><a href="#" className="hover:text-accent-cyan transition-colors">{item}</a></div>
            ))}
          </div>
        </div>

        <div>
          <div className="font-bold mb-4 text-sm tracking-wider">CONTATO</div>
          <div className="space-y-3 text-sm text-neutral-400">
            <div>contato@marefuturo.org.br</div>
            <div>+55 (83) 3000-0000</div>
            <div>Cabedelo, Paraíba, Brasil</div>
          </div>
        </div>
      </div>

      <div className="border-t pt-8" style={{ borderColor: COLORS.neutralBlack }}>
        <div className="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-neutral-500">
          <div>© 2025 Instituto Maré Futuro. Todos os direitos reservados.</div>
          <div className="flex gap-6">
            <a href="#" className="hover:text-accent-cyan transition-colors">Política de Privacidade</a>
            <a href="#" className="hover:text-accent-cyan transition-colors">Termos de Uso</a>
          </div>
        </div>
      </div>
    </div>
  </footer>
);

// --- Componente Principal (Simulando Index.jsx) ---
const InstitutoMareFuturo = () => {
  const [menuOpen, setMenuOpen] = useState(false);
  const [selectedRobot, setSelectedRobot] = useState(null);
  
  // --- FIREBASE STATES ---
  const [db, setDb] = useState(null);
  const [userId, setUserId] = useState(null);
  const [isFirebaseReady, setIsFirebaseReady] = useState(false);

  // --- MODAL STATES ---
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [modalTitle, setModalTitle] = useState('');
  const [modalMessage, setModalMessage] = useState('');
  const [modalIsSuccess, setModalIsSuccess] = useState(false);

  const openModal = (isSuccess, title, message) => {
    setModalIsSuccess(isSuccess);
    setModalTitle(title);
    setModalMessage(message);
    setIsModalOpen(true);
  };
  
  // --- FIREBASE INITIALIZATION AND AUTHENTICATION ---
  useEffect(() => {
    try {
        const firebaseConfig = typeof __firebase_config !== 'undefined' ? JSON.parse(__firebase_config) : {};
        const app = initializeApp(firebaseConfig);
        const firestore = getFirestore(app);
        const firebaseAuth = getAuth(app);

        setDb(firestore);

        // Define a função de autenticação
        const authenticate = async () => {
            try {
                if (typeof __initial_auth_token !== 'undefined' && __initial_auth_token !== '') {
                    await signInWithCustomToken(firebaseAuth, __initial_auth_token);
                } else {
                    await signInAnonymously(firebaseAuth);
                }
            } catch (error) {
                console.error("Authentication Error:", error);
                await signInAnonymously(firebaseAuth); // Fallback to anonymous
            }
        };
        authenticate();

        onAuthStateChanged(firebaseAuth, (user) => {
            if (user) {
                setUserId(user.uid);
            }
            setIsFirebaseReady(true);
        });

    } catch (error) {
        console.error("Falha ao inicializar o Firebase:", error);
    }
  }, []);

  // Utiliza o Custom Hook para gerenciar o estado e a busca de dados do Firestore
  const { isLoading, stats, robots } = useMareFuturoData(db, isFirebaseReady);
  
  // Define o robô selecionado como o primeiro por padrão
  useEffect(() => {
    if (robots.length > 0 && !selectedRobot) {
        setSelectedRobot(robots[0]);
    }
  }, [robots, selectedRobot]);

  return (
    <div className="min-h-screen bg-neutral-50 font-sans">
      <Header menuOpen={menuOpen} setMenuOpen={setMenuOpen} />
      
      <main>
        <HeroSection />
        <ImpactSection isLoading={isLoading} stats={stats} />
        <TrackingSection robots={robots} selectedRobot={selectedRobot} setSelectedRobot={setSelectedRobot} />
        <ResearchSection />
        <SponsorshipForm db={db} userId={userId} openModal={openModal} />
      </main>
      
      <Footer />

      {/* MODAL DE CONFIRMAÇÃO DE ENVIO */}
      <SimpleModal 
        isOpen={isModalOpen} 
        onClose={() => setIsModalOpen(false)} 
        title={modalTitle}
        isSuccess={modalIsSuccess}
      >
        <p className="text-lg mb-4">{modalMessage}</p>
        <button 
          onClick={() => setIsModalOpen(false)} 
          className="w-full px-6 py-3 font-bold text-sm" 
          style={{ backgroundColor: COLORS.accentCyan, color: COLORS.neutralWhite }}
        >
          FECHAR
        </button>
      </SimpleModal>
    </div>
  );
};

export default InstitutoMareFuturo;
