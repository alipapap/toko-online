import { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import axios from "../../api/axios";
import Layout from "../Layouts";

export default function OrderDetail() {
  const { orderId } = useParams();

  const [order, setOrder] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    loadOrder();
  }, [orderId]);

  const loadOrder = async () => {
    try {
      const response = await axios.get(`/orders/${orderId}`);

      setOrder(response.data.order);
    } catch (err) {
      console.error(err);

      setError(
        err.response?.data?.message ||
          "Gagal memuat detail pesanan."
      );
    } finally {
      setLoading(false);
    }
  };

  const formatRupiah = (value) => {
    return "Rp " + Number(value ?? 0).toLocaleString("id-ID");
  };

  const formatDate = (date) => {
    if (!date) return "-";

    return new Date(date).toLocaleDateString("id-ID", {
      day: "2-digit",
      month: "long",
      year: "numeric",
    });
  };

  if (loading) {
    return (
      <Layout>
        <div className="container py-5 text-center">
          <div className="spinner-border text-primary"></div>
          <p className="text-secondary mt-3">
            Memuat detail pesanan...
          </p>
        </div>
      </Layout>
    );
  }

  if (error || !order) {
    return (
      <Layout>
        <div className="container py-5 text-center">
          <div className="alert alert-danger rounded-4">
            {error || "Pesanan tidak ditemukan."}
          </div>

          <Link
            to="/orders"
            className="btn btn-primary rounded-pill"
          >
            ← Kembali ke Pesanan
          </Link>
        </div>
      </Layout>
    );
  }

  const details =
    order.order_details ??
    order.orderDetails ??
    [];

  return (
    <Layout>
      <div className="container py-4">

        {/* BACK */}
        <Link
          to="/orders"
          className="text-decoration-none text-secondary"
        >
          ← Kembali ke Pesanan
        </Link>

        {/* HEADER */}
        <div className="mt-4 mb-4">
          <div className="d-flex justify-content-between align-items-start">

            <div>
              <h1 className="fw-bold mb-1">
                Detail Pesanan
              </h1>

              <p className="text-secondary mb-0">
                Pesanan #{String(order.id).padStart(5, "0")}
              </p>
            </div>

            <span className="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
              {order.status}
            </span>

          </div>
        </div>

        <div className="row g-4">

          {/* ITEMS */}
          <div className="col-lg-8">

            <div className="bg-white border rounded-4 overflow-hidden">

              <div className="p-4 border-bottom">
                <h5 className="fw-bold mb-1">
                  Produk Pesanan
                </h5>

                <small className="text-secondary">
                  Dibuat pada {formatDate(order.created_at)}
                </small>
              </div>

              {details.map((item) => {
                const product = item.product;

                return (
                  <div
                    key={item.id}
                    className="p-4 border-bottom"
                  >
                    <div className="d-flex align-items-center gap-3">

                      <div
                        className="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                        style={{
                          width: "70px",
                          height: "70px",
                          background:
                            "linear-gradient(135deg, #f1efff, #eef2ff)",
                        }}
                      >
                        {product?.image ? (
                          <img
                            src={getImageUrl(product.image)}
                            alt={product.name}
                            style={{
                              width: "100%",
                              height: "100%",
                              objectFit: "cover",
                              borderRadius: "12px",
                            }}
                          />
                        ) : (
                          <span className="fw-bold fs-4 text-primary">
                            {product?.name
                              ?.charAt(0)
                              .toUpperCase() ?? "P"}
                          </span>
                        )}
                      </div>

                      <div className="flex-grow-1">
                        <h6 className="fw-bold mb-1">
                          {product?.name ?? "Produk"}
                        </h6>

                        <small className="text-secondary">
                          {item.quantity} ×{" "}
                          {formatRupiah(item.price)}
                        </small>
                      </div>

                      <strong className="text-primary">
                        {formatRupiah(item.subtotal)}
                      </strong>

                    </div>
                  </div>
                );
              })}

            </div>

          </div>

          {/* SUMMARY */}
          <div className="col-lg-4">

            <div className="bg-white border rounded-4 p-4">

              <h5 className="fw-bold mb-4">
                Ringkasan
              </h5>

              <div className="d-flex justify-content-between mb-3">
                <span className="text-secondary">
                  Total Produk
                </span>

                <strong>
                  {details.reduce(
                    (sum, item) =>
                      sum + Number(item.quantity ?? 0),
                    0
                  )}
                </strong>
              </div>

              <div className="border-top pt-3">

                <div className="d-flex justify-content-between align-items-center">

                  <span className="fw-semibold">
                    Total
                  </span>

                  <strong className="text-primary fs-4">
                    {formatRupiah(order.total)}
                  </strong>

                </div>

              </div>

              {order.status === "pending" && (
                <Link
                  to={`/payment/${order.id}`}
                  className="btn btn-primary w-100 rounded-pill py-3 mt-4"
                >
                  Bayar Sekarang →
                </Link>
              )}

            </div>

          </div>

        </div>
      </div>
    </Layout>
  );
}