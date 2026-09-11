import { useEffect, useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import axios from "../../api/axios";
import Layout from "../Layouts";

export default function Checkout() {
  const [items, setItems] = useState([]);
  const [total, setTotal] = useState(0);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState("");
  const navigate = useNavigate();

  useEffect(() => {
    axios
      .get("/checkout")
      .then((res) => {
        setItems(res.data.items ?? []);
        setTotal(res.data.total ?? 0);
      })
      .catch((err) => {
        setError(err.response?.data?.message || "Gagal memuat data checkout.");
      })
      .finally(() => setLoading(false));
  }, []);

  const formatRupiah = (value) =>
    "Rp " + Number(value ?? 0).toLocaleString("id-ID");

  const handleCheckout = async (e) => {
    e.preventDefault();
    setSubmitting(true);
    setError("");

    try {
      const res = await axios.post("/checkout");
      window.dispatchEvent(new Event("cart-updated"));
      navigate(`/payment/${res.data.order.id}`);
    } catch (err) {
      setError(err.response?.data?.message || "Gagal membuat pesanan.");
    } finally {
      setSubmitting(false);
    }
  };

  if (loading) {
    return (
      <Layout>
        <div className="container py-5 text-center">
          <p>Memuat checkout...</p>
        </div>
      </Layout>
    );
  }

  if (error && items.length === 0) {
    return (
      <Layout>
        <div className="container py-5 text-center">
          <div className="alert alert-warning d-inline-block">{error}</div>
          <div>
            <Link to="/cart" className="btn btn-primary rounded-pill mt-3">
              Kembali ke Keranjang
            </Link>
          </div>
        </div>
      </Layout>
    );
  }

  const totalQuantity = items.reduce((sum, item) => sum + item.quantity, 0);

  return (
    <Layout>
      <div className="container py-4">
        <div className="mb-4">
          <Link to="/cart" className="text-decoration-none text-secondary small">
            ← Kembali ke keranjang
          </Link>
          <h1 className="fw-bold mt-3 mb-1">Checkout</h1>
          <p className="text-secondary mb-0">
            Periksa pesanan sebelum membuat order.
          </p>
        </div>

        <div className="row g-4">
          {/* ORDER ITEMS */}
          <div className="col-lg-8">
            <div className="bg-white rounded-4 shadow-sm border overflow-hidden">
              <div className="p-4 border-bottom">
                <h5 className="fw-bold mb-1">Pesanan Kamu</h5>
                <p className="text-secondary small mb-0">
                  Pastikan produk dan jumlahnya sudah benar.
                </p>
              </div>

              {items.map((item, i) => (
                <div className="p-4 border-bottom" key={i}>
                  <div className="row align-items-center g-3">
                    {/* PRODUCT */}
                    <div className="col-md-6">
                      <div className="d-flex align-items-center gap-3">
                        <div
                          className="flex-shrink-0 rounded-3 d-flex align-items-center justify-content-center"
                          style={{
                            width: "64px",
                            height: "64px",
                            background: "linear-gradient(135deg, #ede9fe, #e0e7ff)",
                          }}
                        >
                          <span className="fw-bold fs-5 text-primary">
                            {item.product.name.charAt(0).toUpperCase()}
                          </span>
                        </div>
                        <div>
                          <h6 className="fw-bold mb-1">{item.product.name}</h6>
                          <small className="text-secondary">
                            {item.product.store?.name ?? "-"}
                          </small>
                        </div>
                      </div>
                    </div>

                    {/* QUANTITY */}
                    <div className="col-md-2">
                      <span className="text-secondary small d-block">Jumlah</span>
                      <strong>{item.quantity}</strong>
                    </div>

                    {/* SUBTOTAL */}
                    <div className="col-md-4 text-md-end">
                      <span className="text-secondary small d-block">Subtotal</span>
                      <strong className="text-primary">
                        {formatRupiah(item.subtotal)}
                      </strong>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* SUMMARY */}
          <div className="col-lg-4">
            <div
              className="bg-white rounded-4 shadow-sm border p-4 sticky-lg-top"
              style={{ top: "90px" }}
            >
              <h5 className="fw-bold mb-4">Ringkasan Pembayaran</h5>

              <div className="d-flex justify-content-between mb-3">
                <span className="text-secondary">Jumlah produk</span>
                <strong>{totalQuantity}</strong>
              </div>

              <div className="border-top pt-3">
                <div className="d-flex justify-content-between align-items-center">
                  <span className="fw-semibold">Total</span>
                  <strong className="fs-4 text-primary">
                    {formatRupiah(total)}
                  </strong>
                </div>
              </div>

              {error && (
                <div className="alert alert-danger mt-3 py-2 small">{error}</div>
              )}

              <form onSubmit={handleCheckout}>
                <button
                  type="submit"
                  className="btn btn-primary w-100 rounded-pill py-3 mt-4"
                  disabled={submitting}
                >
                  {submitting ? "Memproses..." : "Buat Pesanan Sekarang →"}
                </button>
              </form>

              <Link
                to="/cart"
                className="btn btn-light w-100 rounded-pill py-3 mt-2"
              >
                Kembali ke Keranjang
              </Link>

              <div className="text-center mt-4 pt-2 border-top">
                <small className="text-secondary">
                  Butuh bantuan? Hubungi CS kami via WhatsApp.
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Layout>
  );
}