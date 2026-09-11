import { useEffect, useState } from "react";
import { useParams, Link } from "react-router-dom";
import axios from "../../api/axios";
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

  const handleQuantityChange = (e) => {
    const val = parseInt(e.target.value, 10);
    if (isNaN(val) || val < 1) {
      setQuantity(1);
    } else if (val > product.stock) {
      setQuantity(product.stock);
    } else {
      setQuantity(val);
    }
  };

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
        <p className="text-center text-muted py-5">Memuat produk...</p>
      </Layout>
    );
  }

  if (!product) {
    return (
      <Layout>
        <p className="text-center text-muted py-5">Produk tidak ditemukan.</p>
      </Layout>
    );
  }

  return (
    <Layout>
      {/* CONTAINER UTAMA (Tailwind max-w & Bootstrap container/padding) */}
      <div className="container py-4" style={{ maxWidth: "1100px" }}>
        
        {/* TOMBOL KEMBALI */}
        <div className="mb-3">
          <Link to="/products" className="text-secondary text-decoration-none small hover:underline">
            ← Kembali ke katalog
          </Link>
        </div>

        {/* GRID UTAMA (Bootstrap Row & Cols untuk Responsivitas) */}
        <div className="row g-4">
          
          {/* KIRI: DETAIL PRODUK (Mengambil 8 dari 12 kolom pada layar besar) */}
          <div className="col-12 col-lg-8">
            <div className="card border rounded-4 shadow-sm overflow-hidden bg-white">
              
              {/* Gambar Produk dengan Gradasi Tailwind */}
              <div 
                className="d-flex align-items-center justify-content-center overflow-hidden"
                style={{ 
                  height: "360px", 
                  background: "linear-gradient(135deg, #ede9fe, #f5f3ff, #e0e7ff)" 
                }}
              >
                {product.image ? (
                  <img src={product.image} alt={product.name} className="w-100 h-100 object-fit-cover" />
                ) : (
                  <div className="bg-white rounded-4 shadow d-flex align-items-center justify-content-center" style={{ width: "128px", height: "128px" }}>
                    <span className="fw-bolder text-purple" style={{ fontSize: "60px", color: "#7c3aed" }}>
                      {product.name?.charAt(0).toUpperCase()}
                    </span>
                  </div>
                )}
              </div>

              {/* Konten Teks Detail */}
              <div className="card-body p-4">
                <small className="text-secondary d-block mb-1">Toko</small>
                <div className="fw-semibold mb-2 text-purple" style={{ color: "#7c3aed" }}>
                  {product.store?.name ?? "-"}
                </div>

                <h1 className="fw-extrabold mb-2 h2 text-dark">{product.name}</h1>

                <div className="fw-extrabold mb-3 h2 text-purple" style={{ color: "#7c3aed" }}>
                  {formatRupiah(product.price)}
                </div>

                {/* Status Stok */}
                <div className="mb-4 d-flex align-items-center">
                  {product.stock > 0 ? (
                    <>
                      <span className="badge bg-success-subtle text-success rounded-pill px-3 py-2 fw-bold">
                        Stok tersedia
                      </span>
                      <span className="text-secondary small ms-2">
                        {product.stock} produk tersedia
                      </span>
                    </>
                  ) : (
                    <span className="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 fw-bold">
                      Stok habis
                    </span>
                  )}
                </div>

                {/* Deskripsi */}
                {product.detail && (
                  <div className="border-top pt-4">
                    <h5 className="fw-bold mb-2">Deskripsi Produk</h5>
                    <p className="text-secondary lh-lg mb-3">{product.detail.description}</p>

                    {product.detail.weight && (
                      <div className="d-flex align-items-center gap-2 small text-secondary">
                        <span>Berat produk:</span>
                        <strong className="text-dark">{product.detail.weight} kg</strong>
                      </div>
                    )}
                  </div>
                )}
              </div>

            </div>
          </div>

          {/* KANAN: PANEL PEMBELIAN (Mengambil 4 dari 12 kolom) */}
          <div className="col-12 col-lg-4">
            <div className="card border rounded-4 p-4 shadow-sm bg-white sticky-lg-top" style={{ top: "110px" }}>
              <h5 className="fw-bold mb-4">Beli Produk</h5>

              <div className="d-flex justify-content-between mb-3 small">
                <span className="text-secondary">Harga satuan</span>
                <strong className="text-dark">{formatRupiah(product.price)}</strong>
              </div>

              <div className="d-flex justify-content-between mb-3 small">
                <span className="text-secondary">Stok</span>
                <strong className="text-dark">{product.stock}</strong>
              </div>

              {/* Status Pesan Sukses / Gagal */}
              {message && (
                <div className="alert alert-purple text-purple border-0 small mb-3 fw-medium" style={{ backgroundColor: "#f3e8ff", color: "#6b21a8" }}>
                  {message}
                </div>
              )}

              {isLoggedIn ? (
                product.stock > 0 ? (
                  <form onSubmit={handleAddToCart}>
                    <div className="mb-3">
                      <label htmlFor="quantity" className="form-label fw-bold small mb-2">Jumlah</label>
                      <input
                        id="quantity"
                        type="number"
                        className="form-control form-control-lg rounded-3 fs-6"
                        value={quantity}
                        onChange={handleQuantityChange}
                        min="1"
                        max={product.stock}
                        required
                      />
                      <small className="text-secondary d-block mt-2" style={{ fontSize: "11px" }}>
                        Maksimal {product.stock} produk.
                      </small>
                    </div>

                    <button 
                      type="submit" 
                      className="btn text-white w-100 rounded-pill py-3 fw-bold shadow-sm transition" 
                      style={{ backgroundColor: "#7c3aed" }}
                      disabled={adding}
                    >
                      {adding ? "Menambahkan..." : "Tambah ke Keranjang"}
                    </button>
                  </form>
                ) : (
                  <button className="btn btn-secondary w-100 rounded-pill py-3 fw-bold" disabled>
                    Stok Habis
                  </button>
                )
              ) : (
                <div className="text-center">
                  <Link 
                    to="/login" 
                    className="btn text-white w-100 rounded-pill py-3 fw-bold mb-3 shadow-sm"
                    style={{ backgroundColor: "#7c3aed" }}
                  >
                    Login untuk Membeli
                  </Link>
                  <p className="text-secondary small mb-0">
                    Belum punya akun?{" "}
                    <Link to="/register" className="text-purple fw-bold text-decoration-none" style={{ color: "#7c3aed" }}>
                      Daftar sekarang
                    </Link>
                  </p>
                </div>
              )}
            </div>
          </div>

        </div>
      </div>
    </Layout>
  );
}
