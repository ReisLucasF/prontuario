const express = require('express');
const cors = require('cors');
const app = express();
const morgan = require('morgan');

// DATABASE
require('./database');

// Middleware 
const apiKeyMiddleware = (req, res, next) => {
  const apiKey = req.headers['x-api-key'];

  if (apiKey !== process.env.API_KEY) {
    return res.status(401).json({ message: 'Acesso negado. Chave da API inválida.' });
  }

  next();
};

const estoqueRoutes = require('./routes/estoqueRoutes');
const pacienteRoutes = require('./routes/pacientesRoutes');
const usuarioRoutes = require('./routes/usuarioRoutes');
const atendimentoRoutes = require('./routes/atendimentoRoutes');
const receitaRoutes = require('./routes/receitaRoutes');
const solicitacaoExameRoutes = require('./routes/solicitacaoexameRoute');

app.use(morgan('dev'));
app.use(express.json()); 
app.use(cors());
app.use(apiKeyMiddleware);
app.use('/estoque', estoqueRoutes);
app.use('/pacientes', pacienteRoutes);
app.use('/usuarios', usuarioRoutes);
app.use('/atendimentos', atendimentoRoutes);
app.use('/receita', receitaRoutes);
app.use('/solicitacaoExames', solicitacaoExameRoutes);

const PORT = 3001;

app.listen(PORT, () => {
  console.log(`API rodando na porta http://localhost:${PORT}\n\nPara pausar a API, pressione Ctrl + C`);
});
