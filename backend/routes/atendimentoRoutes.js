const express = require('express');
const router = express.Router();
const Atendimento = require('../models/atendimento');

// Listar todos os atendimentos
router.get('/', async (req, res) => {
  try {
    const atendimentos = await Atendimento.find({})
      .populate('medico', 'nome') // Popula o nome do médico
      .populate('paciente', 'nome'); // Popula o nome do paciente
    res.json(atendimentos);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Criar um novo atendimento
router.post('/', async (req, res) => {
  try {
    const { descricao, exameSolicitado, medico, paciente } = req.body;
    const novoAtendimento = new Atendimento({
      descricao,
      exameSolicitado,
      medico,
      paciente
    });
    await novoAtendimento.save();
    res.status(201).json({ message: 'Atendimento criado com sucesso!' });
  } catch (error) {
    res.status(400).json({ error: 'Falha ao criar atendimento', details: error });
  }
});

module.exports = router;