const express = require('express');
const router = express.Router();
const Estoque = require('../models/estoque');

// Adicionar múltiplos medicamentos (para fins de teste)
router.post('/medicamentos/multiplos', async (req, res) => {
    try {
        const medicamentos = req.body;
        const resultados = await Estoque.insertMany(medicamentos);
        res.status(201).send({ message: 'Medicamentos adicionados com sucesso!', data: resultados });
    } catch (error) {
        res.status(400).send({ error: 'Falha ao adicionar medicamentos', details: error });
    }
});

// POST
// router.post('/medicamentos', async (req, res) => {
//     try {
//         const novoMedicamento = new Estoque(req.body);
//         await novoMedicamento.save();
//         res.status(201).send({ message: 'Medicamento adicionado com sucesso!' });
//     } catch (error) {
//         res.status(400).send({ error: 'Falha ao adicionar medicamento', details: error });
//     }
// });

router.post('/medicamentos', async (req, res) => {
    const { nome, codigo } = req.body;

    try {
        const medicamentoExistente = await Estoque.findOne({ $or: [{ nome }, { codigo }] });

        if (medicamentoExistente) {
            return res.status(400).send({ message: 'Medicamento já existe no banco de dados.' });
        }

        const novoMedicamento = new Estoque(req.body);
        await novoMedicamento.save();
        res.status(201).send({ message: 'Medicamento adicionado com sucesso!' });

    } catch (error) {
        res.status(400).send({ error: 'Falha ao adicionar medicamento', details: error });
    }
});



// LIST
router.get('/', async (req, res) => {
  try {
    const results = await Estoque.find({});
    res.json(results);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});


// Delete
router.delete('/excluir/:id', async (req, res) => {
  try {
    const result = await Estoque.findByIdAndDelete(req.params.id);
    if (!result) {
      return res.status(404).json({ mensagem: "Medicamento não encontrado ou já excluído." });
    }
    res.status(200).json({ mensagem: "Medicamento excluído com sucesso." });
  } catch (error) {
    console.error('Erro ao excluir o medicamento:', error);
    res.status(500).json({ erro: 'Erro ao excluir o medicamento' });
  }
});

// Update
router.put('/medicamento/:id', async (req, res) => {
  const { nome, codigo, preco, quantidade } = req.body;
  try {
    const result = await Estoque.findByIdAndUpdate(req.params.id, {
      nome,
      codigo,
      preco,
      quantidade,
    }, { new: true });
    if (!result) {
      return res.status(404).json({ mensagem: "Medicamento não encontrado." });
    }
    res.status(200).json({ mensagem: "Medicamento atualizado com sucesso.", data: result });
  } catch (error) {
    res.status(500).json({ erro: error.message });
  }
});

// Listar apenas medicamentos disponíveis
router.get('/disponiveis', async (req, res) => {
  try {
    const medicamentos = await Estoque.find({ quantidade: { $gt: 0 } });
    res.json(medicamentos);
  } catch (error) {
    res.status(500).json({ error: error.message }); 
  }
});


// em breve: validação se o medicamento requisitado possui quantidade > 0  em estoque


module.exports = router;
