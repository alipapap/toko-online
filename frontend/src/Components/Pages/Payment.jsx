import { useEffect, useState } from "react";
import { useParams, useNavigate, Link } from "react-router-dom";
import axios from "../../api/axios";
import Layout from "../Layouts";

export default function Payment() {
  const { orderId } = useParams();
  const navigate = useNavigate();

  const [order, setOrder] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [submitting, setSubmitting] = useState(false);

  const [method, setMethod] = useState("Transfer Bank");
  const [ewalletProvider, setEwalletProvider] = useState("");

  useEffect(() => {
    axios
      .get(`/payment/${orderId}`)
      .then((res) => {
        if (res.data.already_paid) {
          navigate(`/orders/${orderId}`);
          return;
        }
        setOrder(res.data.order);
      })
      .catch((err) => {
        setError(err.response?.data?.message || "Gagal memuat data pembayaran.");
      })
      .finally(() => setLoading(false));
  }, [orderId]);

  const formatRupiah = (value) =>
    "Rp " + Number(value ?? 0).toLocaleString("id-ID");

  const showQr = method === "Transfer Bank" || method === "E-Wallet";
  const showEwalletProviders = method === "E-Wallet";

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSubmitting(true);
    setError("");

    try {
      await axios.post(`/payment/${orderId}`, { method });
      navigate(`/orders/${orderId}`);
    } catch (err) {
      setError(err.response?.data?.message || "Gagal memproses pembayaran.");
    } finally {
      setSubmitting(false);
    }
  };

  if (loading) {
    return (
      <Layout>
        <div className="container py-5 text-center">
          <p>Memuat pembayaran...</p>
        </div>
      </Layout>
    );
  }

  if (error && !order) {
    return (
      <Layout>
        <div className="container py-5 text-center">
          <div className="alert alert-warning d-inline-block">{error}</div>
        </div>
      </Layout>
    );
  }

  return (
    <Layout>
      <div className="container py-4">
        <div className="row justify-content-center">
          <div className="col-lg-8">
            <div className="mb-4">
              <Link
                to={`/orders/${orderId}`}
                className="text-decoration-none text-secondary small"
              >
                ← Kembali ke pesanan
              </Link>
              <h1 className="fw-bold mt-3 mb-1">Pembayaran</h1>
              <p className="text-secondary mb-0">Pesanan #{order.id}</p>
            </div>

            <div className="bg-white rounded-4 shadow-sm border overflow-hidden">
              {/* TOTAL */}
              <div
                className="text-white p-4 p-md-5"
                style={{
                  background: "linear-gradient(to right, #7c3aed, #4f46e5)",
                }}
              >
                <div className="small" style={{ color: "rgba(255,255,255,.7)" }}>
                  Total tagihan
                </div>
                <div className="display-5 fw-bold mt-2">
                  {formatRupiah(order.total_amount)}
                </div>
              </div>

              <div className="p-4 p-md-5">
                <form onSubmit={handleSubmit}>
                  <h5 className="fw-bold mb-4">Pilih Metode Pembayaran</h5>

                  {/* TRANSFER BANK */}
                  <label className="d-block border rounded-4 p-4 mb-3" style={{ cursor: "pointer" }}>
                    <div className="d-flex align-items-start gap-3">
                      <input
                        type="radio"
                        name="method"
                        value="Transfer Bank"
                        checked={method === "Transfer Bank"}
                        onChange={(e) => setMethod(e.target.value)}
                        className="form-check-input mt-1"
                      />
                      <div>
                        <div className="fw-bold">Transfer Bank</div>
                        <div className="text-secondary small mt-1">
                          Lakukan pembayaran melalui transfer bank.
                        </div>
                      </div>
                    </div>
                  </label>

                  {/* COD */}
                  <label className="d-block border rounded-4 p-4 mb-3" style={{ cursor: "pointer" }}>
                    <div className="d-flex align-items-start gap-3">
                      <input
                        type="radio"
                        name="method"
                        value="COD"
                        checked={method === "COD"}
                        onChange={(e) => setMethod(e.target.value)}
                        className="form-check-input mt-1"
                      />
                      <div>
                        <div className="fw-bold">COD</div>
                        <div className="text-secondary small mt-1">
                          Bayar ketika pesanan diterima.
                        </div>
                      </div>
                    </div>
                  </label>

                  {/* E-WALLET */}
                  <label className="d-block border rounded-4 p-4 mb-3" style={{ cursor: "pointer" }}>
                    <div className="d-flex align-items-start gap-3">
                      <input
                        type="radio"
                        name="method"
                        value="E-Wallet"
                        checked={method === "E-Wallet"}
                        onChange={(e) => setMethod(e.target.value)}
                        className="form-check-input mt-1"
                      />
                      <div className="w-100">
                        <div className="fw-bold">E-Wallet</div>
                        <div className="text-secondary small mt-1">
                          Bayar menggunakan dompet digital.
                        </div>

                        {showEwalletProviders && (
                          <div className="mt-3">
                            <div className="d-flex flex-wrap gap-2">
                              {["GoPay", "DANA", "OVO", "ShopeePay"].map((p) => (
                                <button
                                  key={p}
                                  type="button"
                                  className={`btn btn-sm rounded-pill px-3 ${
                                    ewalletProvider === p
                                      ? "btn-primary"
                                      : "btn-outline-secondary"
                                  }`}
                                  onClick={() => setEwalletProvider(p)}
                                >
                                  {p}
                                </button>
                              ))}
                            </div>
                            <div className="text-secondary small mt-2">
                              Semua aplikasi di atas mendukung scan QR yang sama di bawah.
                            </div>
                          </div>
                        )}
                      </div>
                    </div>
                  </label>

                  {/* QR CODE */}
                  {showQr && (
                    <div className="text-center border rounded-4 p-4 mb-4 bg-light mt-2">
                      <div className="fw-semibold mb-3">
                        Scan QR untuk pesanan ini
                      </div>
                      <img
                        src={`http://127.0.0.1:8000/api/payment/${order.id}/qr`}
                        alt={`QR Pesanan #${order.id}`}
                        style={{ maxWidth: "260px", width: "100%" }}
                        className="img-fluid rounded-3 border bg-white p-2"
                      />
                      <div className="text-secondary small mt-3">
                        QR ini berisi info pesanan #{order.id} — hanya untuk keperluan demo,
                        bukan QR pembayaran nyata.
                      </div>
                    </div>
                  )}

                  {error && (
                    <div className="alert alert-danger py-2 small">{error}</div>
                  )}

                  <div className="border-top pt-4">
                    <div className="d-flex justify-content-between mb-4">
                      <span className="text-secondary">Total pembayaran</span>
                      <strong className="text-primary fs-5">
                        {formatRupiah(order.total_amount)}
                      </strong>
                    </div>

                    <button
                      type="submit"
                      className="btn btn-primary w-100 rounded-pill py-3"
                      disabled={submitting}
                    >
                      {submitting ? "Memproses..." : "Bayar Sekarang"}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Layout>
  );
}