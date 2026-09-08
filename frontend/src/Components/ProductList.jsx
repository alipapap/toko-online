import axios from 'axios';
import { useEffect, useState } from 'react';

function ProductList() {
  const [products, setProducts] = useState([]);

  useEffect(() => {
    axios.get('http://127.0.0.1:8000/api/products')
      .then(res => setProducts(res.data.data));
  }, []);

  return (
    <div>
      {products.map(p => (
        <div key={p.id}>
          <img src={p.image} alt={p.name} />
          <h3>{p.name}</h3>
          <p>Rp {p.price}</p>
        </div>
      ))}
    </div>
  );
}

export default ProductList;