import { useEffect, useState } from "react";
import { useParams, Link } from "react-router-dom";
import axios from "../../api/axios";
import "./ProductDetail.css";
import Layout from "../Layouts";

export default function ProductDetail() {
  const { id } = useParams();
  const [product, setProduct] = useState(null);
  const [loading, setLoading] = useState(true);
  const [quantity, setQuantity] = useState(1);
  const [adding, setAdding] = useState(false);
  const [message, setMessage] = useState("");

  const isLoggedIn = !!localStorage.getItem("token");

  useEffect(() => {
    setLoading(true);
    axios
      .get(`/products/${id}`)
      .then((res) => setProduct(res.data.data ?? res.data))
      .catch((err) => console.error(err))
      .finally(() => setLoading(false));
  }, [id]);

  const formatRupiah = (value) =>
    "Rp " + Number(value ?? 0).toLocaleString("id-ID");

  const handleAddToCart = async (e) => {
    e.preventDefault();
    setAdding(true);
    setMessage("");
    try {
      await axios.post(`/cart/${id}`, { quantity });
      setMessage("Produk berhasil ditambahkan ke keranjang!");
      window.dispatchEvent(new Event("cart-updated"));
    } catch (err) {
      setMessage("Gagal menambahkan produk ke keranjang.");
    } finally {
      setAdding(false);
    }
  };

  if (loading) {
    return (
      <Layout>
        <p className="detail-loading">Memuat produk...</p>
      </Layout>
    );
  }

  if (!product) {
    return (
      <Layout>
        <p className="detail-loading">Produk tidak ditemukan.</p>
      </Layout>
    );
  }

  return (
    <Layout>
      <div className="product-detail-page">
        <div className="detail-back">
          <Link to="/products">← Kembali ke katalog</Link>
        </div>

        <div className="detail-grid">
          {/* PRODUCT INFO */}
          <div className="detail-main">
            <div className="detail-card">
              <div className="detail-image">
                {product.image ? (
                  <img src={product.image} alt={product.name} />
                ) : (
                  <div className="detail-letter">
                    <span>{product.name?.charAt(0).toUpperCase()}</span>
                  </div>
                )}
              </div>

              <div className="detail-body">
                <div className="detail-store-label">Toko</div>
                <div className="detail-store-name">
                  {product.store?.name ?? "-"}
                </div>

                <h1 className="detail-title">{product.name}</h1>

                <div className="detail-price">
                  {formatRupiah(product.price)}
                </div>

                <div className="detail-stock-row">
                  {product.stock > 0 ? (
                    <>
                      <span className="badge-stock-ok">Stok tersedia</span>
                      <span className="detail-stock-text">
                        {product.stock} produk tersedia
                      </span>
                    </>
                  ) : (
                    <span className="badge-stock-empty">Stok habis</span>
                  )}
                </div>

                {product.detail && (
                  <div className="detail-description">
                    <h5>Deskripsi Produk</h5>
                    <p>{product.detail.description}</p>

                    {product.detail.weight && (
                      <div className="detail-weight">
                        <span>Berat produk:</span>
                        <strong>{product.detail.weight} kg</strong>
                      </div>
                    )}
                  </div>
                )}
              </div>
            </div>
          </div>

          {/* BUY PANEL */}
          <div className="detail-side">
            <div className="buy-panel">
              <h5>Beli Produk</h5>

              <div className="buy-row">
                <span>Harga satuan</span>
                <strong>{formatRupiah(product.price)}</strong>
              </div>

              <div className="buy-row">
                <span>Stok</span>
                <strong>{product.stock}</strong>
              </div>

              {message && <div className="buy-message">{message}</div>}

              {isLoggedIn ? (
                product.stock > 0 ? (
                  <form onSubmit={handleAddToCart}>
                    <div className="buy-quantity">
                      <label htmlFor="quantity">Jumlah</label>
                      <input
                        id="quantity"
                        type="number"
                        value={quantity}
                        onChange={(e) => setQuantity(e.target.value)}
                        min="1"
                        max={product.stock}
                        required
                      />
                      <small>Maksimal {product.stock} produk.</small>
                    </div>

                    <button type="submit" className="btn-buy" disabled={adding}>
                      {adding ? "Menambahkan..." : "Tambah ke Keranjang"}
                    </button>
                  </form>
                ) : (
                  <button className="btn-buy-disabled" disabled>
                    Stok Habis
                  </button>
                )
              ) : (
                <>
                  <Link to="/login" className="btn-buy">
                    Login untuk Membeli
                  </Link>
                  <p className="buy-register-hint">
                    Belum punya akun?{" "}
                    <Link to="/register">Daftar sekarang</Link>
                  </p>
                </>
              )}
            </div>
          </div>
        </div>
      </div>
    </Layout>
  );
}