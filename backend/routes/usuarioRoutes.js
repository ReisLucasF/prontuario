const express = require('express');
const router = express.Router();
const Usuario = require('../models/usuario');

// Adicionar múltiplos usuários (para fins de teste)
router.post('/criar/multiplos', async (req, res) => {
    try {
        const novosUsuarios = req.body;
        const resultados = await Usuario.insertMany(novosUsuarios);
        res.status(201).send({ message: 'usuários adicionados com sucesso!', data: resultados });
    } catch (error) {
        res.status(400).send({ error: 'Falha ao adicionar usuários', details: error });
    }
});

// Adicionar novo usuário
router.post('/criar', async (req, res) => {
    try {
        const novoUsuario = new Usuario(req.body);
        await novoUsuario.save();
        res.status(201).send({ message: 'Usuário adicionado com sucesso!' });
    } catch (error) {
        res.status(400).send({ error: 'Falha ao adicionar usuário', details: error });
    }
});

// LIST
router.get('/', async (req, res) => {
  try {
    const results = await Usuario.find({});
    res.json(results);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Delete
router.delete('/excluir/:id', async (req, res) => {
  try {
    const result = await Usuario.findByIdAndDelete(req.params.id);
    if (!result) {
      return res.status(404).json({ mensagem: "Usuário não encontrado ou já excluído." });
    }
    res.status(200).json({ mensagem: "Usuário excluído com sucesso." });
  } catch (error) {
    console.error('Erro ao excluir o usuário:', error);
    res.status(500).json({ erro: 'Erro ao excluir o usuário' });
  }
});

// Update
router.put('/atualizar/:id', async (req, res) => {
  const { nome, crm, email, cpf, senha, setor } = req.body;
  try {
    const result = await Usuario.findByIdAndUpdate(req.params.id, {
      nome,
      crm,
      email,
      cpf,
      senha,
      setor,
    }, { new: true });
    if (!result) {
      return res.status(404).json({ mensagem: "Usuário não encontrado." });
    }
    res.status(200).json({ mensagem: "Usuário atualizado com sucesso.", data: result });
  } catch (error) {
    res.status(500).json({ erro: error.message });
  }
});

module.exports = router;
