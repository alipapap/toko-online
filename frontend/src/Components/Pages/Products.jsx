import { useEffect, useState } from "react";
import { useSearchParams, Link } from "react-router-dom";
import axios from "../../api/axios";
import "./Products.css";
import Layout from "../Layouts";

export default function Products() {
  const [searchParams, setSearchParams] = useSearchParams();
  const initialStoreId = searchParams.get("store_id") ?? "";

  const [allProducts, setAllProducts] = useState([]);
  const [stores, setStores] = useState([]);
  const [loading, setLoading] = useState(true);

  const [keyword, setKeyword] = useState("");
  const [storeId, setStoreId] = useState(initialStoreId);

  useEffect(() => {
    setLoading(true);
    Promise.all([axios.get("/products"), axios.get("/stores")])
      .then(([resProducts, resStores]) => {
        setAllProducts(resProducts.data.data ?? resProducts.data);
        setStores(resStores.data.data ?? resStores.data);
      })
      .catch((err) => console.error(err))
      .finally(() => setLoading(false));
  }, []);

  const formatRupiah = (value) =>
    "Rp " + Number(value ?? 0).toLocaleString("id-ID");

  const filteredProducts = allProducts.filter((p) => {
    const matchKeyword = p.name
      ?.toLowerCase()
      .includes(keyword.toLowerCase());
    const matchStore = storeId ? String(p.store_id) === String(storeId) : true;
    return matchKeyword && matchStore;
  });

  const handleSearch = (e) => {
    e.preventDefault();
    setSearchParams(storeId ? { store_id: storeId } : {});
  };

  const storeName = (id) => stores.find((s) => String(s.id) === String(id))?.name;

  return (
  <Layout>
    <div className="products-page">

      <div className="products-container">
        {/* HERO */}
        <section className="products-hero">
          <div>
            <span className="hero-badge-small">Belanja lebih mudah</span>
            <h1>Temukan Produk Favoritmu</h1>
            <p>
              Temukan berbagai produk pilihan dari toko-toko yang tersedia di
              TokoKita.
            </p>
            <Link to="/" className="btn-white">Mulai Belanja</Link>
          </div>
          <div className="hero-logo-circle">
            <div>TK</div>
            <span>TokoKita</span>
          </div>
        </section>

        {/* SEARCH */}
        <section className="products-search">
          <h3>Cari Produk</h3>
          <p>Gunakan pencarian atau pilih toko untuk menemukan produk.</p>

          <form className="search-form" onSubmit={handleSearch}>
            <div className="search-field">
              <label>Nama Produk</label>
              <input
                type="text"
                placeholder="Cari produk..."
                value={keyword}
                onChange={(e) => setKeyword(e.target.value)}
              />
            </div>

            <div className="search-field">
              <label>Toko</label>
              <select
                value={storeId}
                onChange={(e) => setStoreId(e.target.value)}
              >
                <option value="">Semua toko</option>
                {stores.map((s) => (
                  <option key={s.id} value={s.id}>{s.name}</option>
                ))}
              </select>
            </div>

            <button type="submit" className="btn-search">Cari</button>
          </form>
        </section>

        {/* PRODUCT GRID */}
        <section className="products-list">
          <div className="products-list-header">
            <div>
              <h3>Produk Pilihan</h3>
              <p>Pilihan produk yang tersedia saat ini.</p>
            </div>
            <span className="products-count">
              {filteredProducts.length} Produk
            </span>
          </div>

          {loading ? (
            <p>Memuat produk...</p>
          ) : filteredProducts.length === 0 ? (
            <p>Belum ada produk yang cocok.</p>
          ) : (
            <div className="products-grid">
              {filteredProducts.map((product) => (
                <div className="product-card" key={product.id}>
                  <div className="product-card-image">
                    {product.image ? (
                      <img src={product.image} alt={product.name} />
                    ) : (
                      <span>{product.name?.charAt(0).toUpperCase()}</span>
                    )}
                  </div>
                  <div className="product-card-body">
                    <small>{storeName(product.store_id) ?? "TokoKita"}</small>
                    <h4>{product.name}</h4>
                    <div className="product-price">
                      {formatRupiah(product.price)}
                    </div>
                    <div className="product-stock">
                      Stok tersedia: <b>{product.stock}</b>
                    </div>
                    <Link to={`/products/${product.id}`} className="btn-detail">
                      Lihat Detail
                    </Link>
                  </div>
                </div>
              ))}
            </div>
          )}
        </section>
      </div>
     </div>
  </Layout>
  );
}