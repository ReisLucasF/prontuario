const mongoose = require('mongoose');
const Schema = mongoose.Schema;
const usuario = require('./usuario')

const ReceitaMedicaSchema = new Schema({
    atendimentoRef: {
        AtendimentoId: {
            type: mongoose.Types.ObjectId,
            ref: 'atendimento',
            required: true,
        },
        medicoId: {
            type: mongoose.Types.ObjectId,
            ref: 'Usuario',
            required: true,
            autopopulate: true,
        },
        pacienteId: {
            type: mongoose.Types.ObjectId,
            ref: 'Paciente',
            required: true,
            autopopulate: true,
        },
    },
    dataInicio:{ type: Date, required: true },
    dataFim: { type: Date, required: true },
    medicamentos: [{
        nome: { type: String, required: true },
        quantidade: { type: Number, required: true },
        periodo: { type: String, required: true }
    }],
    observacoes: {
        type: String
    }
});

// Habilita o autopopulate globalmente no esquema
ReceitaMedicaSchema.plugin(require('mongoose-autopopulate'));

module.exports = mongoose.model('ReceitaMedica', ReceitaMedicaSchema);
