import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import axios from "../../api/axios"; // sesuaikan path kalau berbeda di project kamu
import "./Home.css";

export default function Home() {
  const [stores, setStores] = useState([]);
  const [products, setProducts] = useState([]);

  useEffect(() => {
    // GANTI endpoint di bawah sesuai route API kamu yang sebenarnya
    axios
      .get("/stores")
      .then((res) => setStores(res.data.data ?? res.data))
      .catch(() => setStores([]));

    axios
      .get("/products")
      .then((res) => setProducts(res.data.data ?? res.data))
      .catch(() => setProducts([]));
  }, []);

  const formatRupiah = (value) =>
    "Rp " + Number(value ?? 0).toLocaleString("id-ID");

  return (
    <div className="home-page">
      <div className="home-container">
        {/* ===================== HERO ===================== */}
        <section className="hero-shopping">
          <div className="hero-content">
            <div>
              <div className="hero-badge">
                🛍️ Belanja lebih mudah di TokoKita
              </div>

              <h1 className="hero-title">
                Temukan barang yang <span>kamu suka.</span>
              </h1>

              <p className="hero-description">
                Jelajahi berbagai produk pilihan dari toko-toko terpercaya.
                Cari, pilih, dan belanja semuanya dalam satu tempat.
              </p>

              <div className="hero-buttons">
                <Link to="/products" className="btn-shopping btn-primary-shopping">
                  🛒 Mulai Belanja
                </Link>
                <a href="#toko" className="btn-shopping btn-outline-shopping">
                  🏪 Jelajahi Toko
                </a>
              </div>
            </div>

            {/* Ilustrasi shopping */}
            <div className="hero-visual">
              <div className="shopping-circle"></div>

              <div className="floating-card floating-one">
                <div className="floating-card-icon">🎁</div>
                <div>
                  Banyak pilihan
                  <br />
                  <span style={{ color: "#7c3aed" }}>untuk kamu</span>
                </div>
              </div>

              <div className="shopping-bag">
                <div className="bag-logo">TK</div>
                <div className="bag-text">TOKOKITA</div>
              </div>

              <div className="floating-card floating-two">
                <div className="floating-card-icon">⚡</div>
                <div>
                  Belanja cepat
                  <br />
                  <span style={{ color: "#7c3aed" }}>dan praktis</span>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* ===================== FEATURES ===================== */}
        <section className="features">
          <div className="feature">
            <div className="feature-icon">🛍️</div>
            <div>
              <h3>Belanja Praktis</h3>
              <p>Pilih produk tanpa ribet.</p>
            </div>
          </div>

          <div className="feature">
            <div className="feature-icon">🏪</div>
            <div>
              <h3>Banyak Toko</h3>
              <p>Temukan berbagai penjual.</p>
            </div>
          </div>

          <div className="feature">
            <div className="feature-icon">📦</div>
            <div>
              <h3>Pesanan Terorganisir</h3>
              <p>Pantau pesanan dengan mudah.</p>
            </div>
          </div>
        </section>

        {/* ===================== TOKO PILIHAN ===================== */}
        <section className="home-section" id="toko">
          <div className="section-heading">
            <div>
              <h2>🏪 Temukan Toko</h2>
              <p>Jelajahi toko yang tersedia di TokoKita.</p>
            </div>
          </div>

          {stores.length > 0 ? (
            <div className="store-grid">
              {stores.map((store) => (
                <Link
                  key={store.id}
                  to={`/products?store_id=${store.id}`}
                  className="store-card"
                >
                  <div className="store-avatar">
                    {store.name?.charAt(0).toUpperCase()}
                  </div>
                  <h3>{store.name}</h3>
                  <p>{store.address ?? "Toko pilihan TokoKita"}</p>
                </Link>
              ))}
            </div>
          ) : (
            <div className="feature">
              <div className="feature-icon">🏪</div>
              <div>
                <h3>Belum ada toko</h3>
                <p>Toko akan muncul di sini.</p>
              </div>
            </div>
          )}
        </section>

        {/* ===================== PROMO BANNER ===================== */}
        <section className="home-section">
          <div className="promo-banner">
            <div>
              <div className="promo-label">✨ Saatnya Belanja</div>
              <h2>Satu tempat untuk banyak kebutuhan.</h2>
              <p>
                Tidak perlu berpindah-pindah. Temukan produk dari berbagai
                toko dan pilih yang paling cocok untukmu.
              </p>
            </div>
            <div>
              <Link to="/products" className="btn-shopping btn-primary-shopping">
                Lihat Semua Produk →
              </Link>
            </div>
          </div>
        </section>

        {/* ===================== PRODUK PILIHAN ===================== */}
        {products.length > 0 && (
          <section className="home-section">
            <div className="section-heading">
              <div>
                <h2>✨ Sedang Banyak Dilihat</h2>
                <p>Beberapa produk pilihan dari TokoKita.</p>
              </div>
              <Link to="/products" className="section-link">
                Lihat Semuaa
              </Link>
            </div>

            <div className="product-preview">
              {products.slice(0, 4).map((product) => (
                <div className="mini-product" key={product.id}>
                  <div className="mini-product-image">
                    {product.image ? (
                      <img
                        src={`${import.meta.env.VITE_API_URL ?? ""}/storage/${product.image}`}
                        alt={product.name}
                      />
                    ) : (
                      product.name?.charAt(0).toUpperCase()
                    )}
                  </div>

                  <div className="mini-product-body">
                    <small>{product.store?.name ?? "TokoKita"}</small>
                    <h3>{product.name}</h3>
                    <div className="mini-product-price">
                      {formatRupiah(product.price)}
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </section>
        )}

        {/* ===================== FINAL CTA ===================== */}
        <section className="home-section" style={{ paddingBottom: 70 }}>
          <div
            className="promo-banner"
            style={{ background: "linear-gradient(135deg,#7c3aed,#4f46e5)" }}
          >
            <div>
              <div className="promo-label" style={{ color: "#ddd6fe" }}>
                TOKOKITA
              </div>
              <h2>Sudah siap mulai belanja?</h2>
              <p>Temukan produk favoritmu sekarang.</p>
            </div>
            <div>
              <Link to="/products" className="btn-shopping btn-primary-shopping">
                🛒 Belanja Sekarang 🛒🛒
              </Link>
            </div>
          </div>
        </section>
      </div>
    </div>
  );
}
