import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import axios from "../../api/axios";
import Layout from "../Layouts";

export default function Home() {
  const [stores, setStores] = useState([]);
  const [products, setProducts] = useState([]);

  useEffect(() => {
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
    <Layout>
      <div className="container py-4">

        {/* ===================== HERO ===================== */}
        <div
          className="position-relative rounded-5 overflow-hidden text-white p-5 mb-4"
          style={{
            minHeight: "400px",
            background: "linear-gradient(135deg, #5b21f5 0%, #7c3aed 45%, #9333ea 100%)",
            boxShadow: "0 25px 60px rgba(91, 33, 245, .22)",
          }}
        >
          <div className="row align-items-center h-100">
            <div className="col-lg-7">
              <span
                className="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill mb-3"
                style={{
                  background: "rgba(255,255,255,.16)",
                  border: "1px solid rgba(255,255,255,.25)",
                  fontSize: "13px",
                  fontWeight: 700,
                }}
              >
                🛍️ Belanja lebih mudah di TokoKita
              </span>

              <h1 className="fw-bold display-4 mb-3">
                Temukan barang yang{" "}
                <span style={{ color: "#fde68a" }}>kamu suka.</span>
              </h1>

              <p className="mb-4" style={{ color: "rgba(255,255,255,.85)", fontSize: "16px", maxWidth: "520px" }}>
                Jelajahi berbagai produk pilihan dari toko-toko terpercaya.
                Cari, pilih, dan belanja semuanya dalam satu tempat.
              </p>

              <div className="d-flex flex-wrap gap-3">
                <Link to="/products" className="btn btn-light btn-lg rounded-3 fw-bold">
                  🛒 Mulai Belanja
                </Link>
                
                 <a href="#toko"
                  className="btn btn-outline-light btn-lg rounded-3 fw-bold"
                >
                  🏪 Jelajahi Toko
                </a>
              </div>
            </div>

            <div className="col-lg-5 d-none d-lg-flex justify-content-center">
              <div
                className="bg-white rounded-4 shadow-lg d-flex flex-column align-items-center justify-content-center"
                style={{ width: "190px", height: "205px", transform: "rotate(5deg)" }}
              >
                <div
                  className="rounded-4 d-flex align-items-center justify-content-center text-white fw-bold mb-3"
                  style={{ width: "65px", height: "65px", background: "#7c3aed", fontSize: "27px" }}
                >
                  TK
                </div>
                <div className="fw-bold" style={{ color: "#5b21f5", fontSize: "14px" }}>
                  TOKOKITA
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* ===================== FEATURES ===================== */}
        <div className="row g-3 mb-5">
          <div className="col-md-4">
            <div className="bg-white border rounded-4 p-4 d-flex align-items-center gap-3 h-100">
              <div
                className="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                style={{ width: "48px", height: "48px", background: "#f1edff", fontSize: "22px" }}
              >
                🛍️
              </div>
              <div>
                <h6 className="fw-bold mb-1">Belanja Praktis</h6>
                <p className="text-secondary small mb-0">Pilih produk tanpa ribet.</p>
              </div>
            </div>
          </div>

          <div className="col-md-4">
            <div className="bg-white border rounded-4 p-4 d-flex align-items-center gap-3 h-100">
              <div
                className="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                style={{ width: "48px", height: "48px", background: "#f1edff", fontSize: "22px" }}
              >
                🏪
              </div>
              <div>
                <h6 className="fw-bold mb-1">Banyak Toko</h6>
                <p className="text-secondary small mb-0">Temukan berbagai penjual.</p>
              </div>
            </div>
          </div>

          <div className="col-md-4">
            <div className="bg-white border rounded-4 p-4 d-flex align-items-center gap-3 h-100">
              <div
                className="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                style={{ width: "48px", height: "48px", background: "#f1edff", fontSize: "22px" }}
              >
                📦
              </div>
              <div>
                <h6 className="fw-bold mb-1">Pesanan Terorganisir</h6>
                <p className="text-secondary small mb-0">Pantau pesanan dengan mudah.</p>
              </div>
            </div>
          </div>
        </div>

        {/* ===================== TOKO PILIHAN ===================== */}
        <div className="mb-5" id="toko">
          <div className="mb-4">
            <h3 className="fw-bold mb-1">🏪 Temukan Toko</h3>
            <p className="text-secondary mb-0">Jelajahi toko yang tersedia di TokoKita.</p>
          </div>

          {stores.length > 0 ? (
            <div className="row g-3">
              {stores.map((store) => (
                <div className="col-6 col-md-3" key={store.id}>
                  <Link
                    to={`/products?store_id=${store.id}`}
                    className="d-block bg-white border rounded-4 p-4 text-decoration-none text-dark h-100"
                  >
                    <div
                      className="d-flex align-items-center justify-content-center rounded-3 mb-3 fw-bold"
                      style={{
                        width: "55px",
                        height: "55px",
                        background: "linear-gradient(135deg, #ede9fe, #ddd6fe)",
                        color: "#6d28d9",
                        fontSize: "21px",
                      }}
                    >
                      {store.name?.charAt(0).toUpperCase()}
                    </div>
                    <h6 className="fw-bold mb-1">{store.name}</h6>
                    <p className="text-secondary small mb-0">
                      {store.address ?? "Toko pilihan TokoKita"}
                    </p>
                  </Link>
                </div>
              ))}
            </div>
          ) : (
            <div className="bg-white border rounded-4 p-4 d-flex align-items-center gap-3">
              <div
                className="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                style={{ width: "48px", height: "48px", background: "#f1edff", fontSize: "22px" }}
              >
                🏪
              </div>
              <div>
                <h6 className="fw-bold mb-1">Belum ada toko</h6>
                <p className="text-secondary small mb-0">Toko akan muncul di sini.</p>
              </div>
            </div>
          )}
        </div>

        {/* ===================== PROMO BANNER ===================== */}
        <div className="mb-5">
          <div
            className="rounded-4 p-5 text-white d-flex flex-wrap justify-content-between align-items-center gap-4"
            style={{ background: "#111827" }}
          >
            <div>
              <div
                className="fw-bold mb-2"
                style={{ color: "#c4b5fd", fontSize: "12px", textTransform: "uppercase", letterSpacing: "1px" }}
              >
                ✨ Saatnya Belanja
              </div>
              <h3 className="fw-bold mb-2">Satu tempat untuk banyak kebutuhan.</h3>
              <p className="mb-0" style={{ color: "#cbd5e1", maxWidth: "600px" }}>
                Tidak perlu berpindah-pindah. Temukan produk dari berbagai
                toko dan pilih yang paling cocok untukmu.
              </p>
            </div>
            <Link to="/products" className="btn btn-light btn-lg rounded-3 fw-bold">
              Lihat Semua Produk →
            </Link>
          </div>
        </div>

        {/* ===================== PRODUK PILIHAN ===================== */}
        {products.length > 0 && (
          <div className="mb-5">
            <div className="d-flex justify-content-between align-items-end mb-4">
              <div>
                <h3 className="fw-bold mb-1">✨ Sedang Banyak Dilihat</h3>
                <p className="text-secondary mb-0">Beberapa produk pilihan dari TokoKita.</p>
              </div>
              <Link to="/products" className="text-decoration-none fw-bold" style={{ color: "#6d28d9" }}>
                Lihat Semua
              </Link>
            </div>

            <div className="row g-3">
              {products.slice(0, 4).map((product) => (
                <div className="col-6 col-md-3" key={product.id}>
                  <div className="bg-white border rounded-4 overflow-hidden h-100">
                    <div
                      className="d-flex align-items-center justify-content-center"
                      style={{ height: "150px", background: "#f7f5ff", color: "#7c3aed", fontSize: "35px", fontWeight: 900 }}
                    >
                      {product.image ? (
                        <img
                          src={product.image}
                          alt={product.name}
                          className="w-100 h-100"
                          style={{ objectFit: "cover" }}
                        />
                      ) : (
                        product.name?.charAt(0).toUpperCase()
                      )}
                    </div>
                    <div className="p-3">
                      <small className="text-secondary">{product.store?.name ?? "TokoKita"}</small>
                      <h6 className="fw-bold my-1">{product.name}</h6>
                      <div className="fw-bold text-primary">{formatRupiah(product.price)}</div>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}

        {/* ===================== FINAL CTA ===================== */}
        <div className="mb-4">
          <div
            className="rounded-4 p-5 text-white d-flex flex-wrap justify-content-between align-items-center gap-4"
            style={{ background: "linear-gradient(135deg,#7c3aed,#4f46e5)" }}
          >
            <div>
              <div className="fw-bold mb-2" style={{ color: "#ddd6fe", fontSize: "12px", textTransform: "uppercase" }}>
                TOKOKITA
              </div>
              <h3 className="fw-bold mb-2">Sudah siap mulai belanja?</h3>
              <p className="mb-0">Temukan produk favoritmu sekarang.</p>
            </div>
            <Link to="/products" className="btn btn-light btn-lg rounded-3 fw-bold">
              🛒 Belanja Sekarang
            </Link>
          </div>
        </div>
      </div>
    </Layout>
  );
}