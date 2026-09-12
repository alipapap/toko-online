import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import axios from "../../api/axios";
import Layout from "../Layouts";

export default function Orders() {
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    loadOrders();
  }, []);

  const loadOrders = async () => {
    try {
      setLoading(true);
      setError("");

      const response = await axios.get("/orders");

      setOrders(response.data.orders ?? []);
    } catch (err) {
      console.error(err);

      setError(
        err.response?.data?.message ||
          "Gagal mengambil data pesanan."
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

  const statusInfo = (status) => {
    switch (status) {
      case "pending":
        return {
          text: "Menunggu Pembayaran",
          className: "bg-warning-subtle text-warning-emphasis",
        };

      case "paid":
        return {
          text: "Sudah Dibayar",
          className: "bg-info-subtle text-info-emphasis",
        };

      case "processing":
        return {
          text: "Sedang Diproses",
          className: "bg-primary-subtle text-primary",
        };

      case "shipped":
        return {
          text: "Sedang Dikirim",
          className: "bg-primary-subtle text-primary",
        };

      case "completed":
        return {
          text: "Selesai",
          className: "bg-success-subtle text-success",
        };

      case "cancelled":
        return {
          text: "Dibatalkan",
          className: "bg-danger-subtle text-danger",
        };

      default:
        return {
          text: status ?? "Unknown",
          className: "bg-secondary-subtle text-secondary",
        };
    }
  };

  if (loading) {
    return (
      <Layout>
        <div className="container py-5 text-center">
          <div
            className="spinner-border text-primary"
            role="status"
          ></div>

          <p className="text-secondary mt-3">
            Memuat pesanan...
          </p>
        </div>
      </Layout>
    );
  }

  return (
    <Layout>
      <div className="container py-4">

        {/* HEADER */}
        <div className="mb-4">
          <h1 className="fw-bold mb-1">
            Pesanan Saya
          </h1>

          <p className="text-secondary mb-0">
            Lihat semua pesanan yang pernah kamu buat.
          </p>
        </div>

        {/* ERROR */}
        {error && (
          <div className="alert alert-danger rounded-4">
            {error}
          </div>
        )}

        {/* EMPTY */}
        {!error && orders.length === 0 && (
          <div className="bg-white border rounded-4 p-5 text-center">
            <div
              className="mx-auto mb-4 rounded-circle d-flex align-items-center justify-content-center"
              style={{
                width: "90px",
                height: "90px",
                background: "#f1efff",
                fontSize: "40px",
              }}
            >
              📦
            </div>

            <h4 className="fw-bold">
              Belum ada pesanan
            </h4>

            <p className="text-secondary">
              Yuk mulai belanja produk favoritmu.
            </p>

            <Link
              to="/products"
              className="btn btn-primary rounded-pill px-4"
            >
              Mulai Belanja →
            </Link>
          </div>
        )}

        {/* ORDER LIST */}
        <div className="d-flex flex-column gap-3">

          {orders.map((order) => {
            const status = statusInfo(order.status);

            const details =
              order.order_details ??
              order.orderDetails ??
              [];

            const totalQuantity = details.reduce(
              (total, item) =>
                total + Number(item.quantity ?? 0),
              0
            );

            return (
              <div
                key={order.id}
                className="bg-white border rounded-4 overflow-hidden"
                style={{
                  boxShadow:
                    "0 8px 25px rgba(0,0,0,.04)",
                }}
              >

                {/* HEADER ORDER */}
                <div className="p-4 border-bottom">
                  <div className="row align-items-center g-3">

                    <div className="col-md-6">
                      <div className="small text-secondary">
                        Nomor Pesanan
                      </div>

                      <div className="fw-bold fs-5">
                        #{String(order.id).padStart(5, "0")}
                      </div>

                      <small className="text-secondary">
                        {formatDate(order.created_at)}
                      </small>
                    </div>

                    <div className="col-md-6 text-md-end">
                      <span
                        className={`badge rounded-pill px-3 py-2 ${status.className}`}
                      >
                        {status.text}
                      </span>
                    </div>

                  </div>
                </div>

                {/* ITEMS */}
                <div className="p-4">

                  {details.slice(0, 3).map((item) => {
                    const product = item.product;

                    return (
                      <div
                        key={item.id}
                        className="d-flex align-items-center gap-3 py-2"
                      >

                        <div
                          className="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                          style={{
                            width: "60px",
                            height: "60px",
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
                            <span className="fw-bold text-primary fs-5">
                              {product?.name
                                ?.charAt(0)
                                .toUpperCase() ?? "P"}
                            </span>
                          )}
                        </div>

                        <div className="flex-grow-1">
                          <div className="fw-semibold">
                            {product?.name ?? "Produk"}
                          </div>

                          <small className="text-secondary">
                            {item.quantity} ×{" "}
                            {formatRupiah(item.price)}
                          </small>
                        </div>

                        <strong>
                          {formatRupiah(item.subtotal)}
                        </strong>

                      </div>
                    );
                  })}

                  {details.length > 3 && (
                    <div className="text-secondary small mt-2">
                      + {details.length - 3} produk lainnya
                    </div>
                  )}

                  {/* FOOTER */}
                  <div className="border-top mt-3 pt-3">

                    <div className="row align-items-center">

                      <div className="col-md-6">
                        <small className="text-secondary">
                          Total {totalQuantity} item
                        </small>

                        <div className="fw-bold text-primary fs-5">
                          {formatRupiah(order.total)}
                        </div>
                      </div>

                      <div className="col-md-6 text-md-end mt-3 mt-md-0">

                        {order.status === "pending" && (
                          <Link
                            to={`/payment/${order.id}`}
                            className="btn btn-primary rounded-pill px-4 me-2"
                          >
                            Bayar
                          </Link>
                        )}

                        <Link
                          to={`/orders/${order.id}`}
                          className="btn btn-outline-primary rounded-pill px-4"
                        >
                          Detail
                        </Link>

                      </div>

                    </div>

                  </div>

                </div>
              </div>
            );
          })}

        </div>
      </div>
    </Layout>
  );
}