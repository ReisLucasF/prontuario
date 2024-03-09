const express = require('express');
const cors = require('cors');
const app = express();

// DATABASE
require('./database');


const estoqueRoutes = require('./routes/estoqueRoutes');
const pacienteRoutes = require('./routes/pacientesRoutes');
const usuarioRoutes = require('./routes/usuarioRoutes');
const atendimentoRoutes = require('./routes/atendimentoRoutes');
const receitaRoutes = require('./routes/receitaRoutes');

app.use(express.json()); 
app.use(cors());
app.use('/estoque', estoqueRoutes);
app.use('/pacientes', pacienteRoutes);
app.use('/usuarios', usuarioRoutes);
app.use('/atendimentos', atendimentoRoutes);
app.use('/receita', receitaRoutes);

const PORT = 3001;

app.listen(PORT, () => {
  console.log(`Servidor rodando na porta http://localhost:${PORT}`);
});
