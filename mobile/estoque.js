import React, { useState, useEffect } from 'react';
import { View, Text, FlatList, StyleSheet } from 'react-native';

const EstoqueScreen = () => {
  const [estoque, setEstoque] = useState([]);

  useEffect(() => {
  const apiKey = 'K3Y001Teste1@#'; // Defina sua chave da API aqui

  fetch('http://192.168.100.2:3001/estoque', {
    headers: {
      'x-api-key': apiKey
    }
  })
      .then(response => response.json())
      .then(data => setEstoque(data))
      .catch(error => console.error('Erro ao buscar estoque:', error));
  }, []);

  const renderItem = ({ item }) => (
    <View style={styles.item}>
      <Text>{item.nome}</Text>
      <Text>Código: {item.codigo}</Text>
      <Text>Preço: R${item.preco.toFixed(2)}</Text>
      <Text>Quantidade: {item.quantidade}</Text>
    </View>
  );

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Estoque</Text>
      <FlatList
        data={estoque}
        renderItem={renderItem}
        keyExtractor={item => item._id}
      />
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 16,
    backgroundColor: '#fff',
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    marginBottom: 16,
  },
  item: {
    backgroundColor: '#f9c2ff',
    padding: 20,
    marginVertical: 8,
    borderRadius: 8,
  },
});

export default EstoqueScreen;
