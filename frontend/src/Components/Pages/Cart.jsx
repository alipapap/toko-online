import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import axios from "../../api/axios";
import Layout from "../Layouts";

export default function Cart() {
  const [items, setItems] = useState([]);
  const [total, setTotal] = useState(0);
  const [loading, setLoading] = useState(true);

  const fetchCart = () => {
    setLoading(true);
    axios
      .get("/cart")
      .then((res) => {
        setItems(res.data.items ?? []);
        setTotal(res.data.total ?? 0);
      })
      .catch((err) => console.error(err))
      .finally(() => setLoading(false));
  };

  useEffect(() => {
    fetchCart();
  }, []);

  const formatRupiah = (value) =>
    "Rp " + Number(value ?? 0).toLocaleString("id-ID");

  const getImageUrl = (image) => {
    if (!image) return null;
    if (image.startsWith("http")) return image;
    return `http://127.0.0.1:8000/storage/${image}`;
  };

  const handleUpdateQuantity = async (productId, quantity) => {
    try {
      await axios.patch(`/cart/${productId}`, { quantity });
      fetchCart();
      window.dispatchEvent(new Event("cart-updated"));
    } catch (err) {
      console.error(err);
    }
  };

  const handleRemove = async (productId) => {
    try {
      await axios.delete(`/cart/${productId}`);
      fetchCart();
      window.dispatchEvent(new Event("cart-updated"));
    } catch (err) {
      console.error(err);
    }
  };

  const totalQuantity = items.reduce((sum, item) => sum + item.quantity, 0);

  if (loading) {
    return (
      <Layout>
        <div className="container py-5 text-center">
          <p>Memuat keranjang...</p>
        </div>
      </Layout>
    );
  }

    return (
    <Layout>
      <div className="container py-5">
        <div className="mb-5">
          <h1 className="fw-bold mb-2">Keranjang Belanja</h1>
          <p className="text-secondary mb-0">
            Periksa kembali produk sebelum melanjutkan ke checkout.
          </p>
        </div>

        {items.length === 0 ? (
          <div className="bg-white rounded-4 shadow-sm border p-5 text-center">
            <div
              className="mx-auto mb-4 rounded-circle bg-light d-flex align-items-center justify-content-center"
              style={{ width: "90px", height: "90px" }}
            >
              <span className="fs-2 fw-bold text-secondary">0</span>
            </div>

            <h3 className="fw-bold mb-2">Keranjang masih kosong</h3>

            <p className="text-secondary mb-4">
              Belum ada produk yang kamu tambahkan ke keranjang.
            </p>

            <Link
              to="/products"
              className="btn btn-primary rounded-pill px-5 py-2"
            >
              Mulai Belanja
            </Link>
          </div>
        ) : (
          <div className="row g-4">

            {/* CART ITEMS */}
            <div className="col-lg-8">
              <div className="bg-white rounded-4 shadow-sm border overflow-hidden">

                {items.map((item) => (
                  <div
                    className="p-5 border-bottom"
                    key={item.id}
                  >
                    <div className="row align-items-center g-4">

                      {/* PRODUCT */}
                      <div className="col-md-5">
                        <div className="d-flex align-items-center gap-4">

                          <div
                            className="flex-shrink-0 rounded-4 overflow-hidden d-flex align-items-center justify-content-center"
                            style={{
                              width: "150px",
                              height: "150px",
                              background: "#f5f3ff",
                            }}
                          >
                            {getImageUrl(item.product.image) ? (
                              <img
                                src={getImageUrl(item.product.image)}
                                alt={item.product.name}
                                style={{
                                  width: "100%",
                                  height: "100%",
                                  objectFit: "contain",
                                  padding: "8px",
                                }}
                              />
                            ) : (
                              <span className="fs-1 fw-bold text-primary">
                                {item.product.name.charAt(0).toUpperCase()}
                              </span>
                            )}
                          </div>

                          <div>
                            <h5 className="fw-bold mb-2">
                              {item.product.name}
                            </h5>

                            <div className="text-secondary">
                              {item.product.store?.name ?? "-"}
                            </div>
                          </div>

                        </div>
                      </div>

                      {/* PRICE */}
                      <div className="col-md-2">
                        <small className="text-secondary d-block mb-2">
                          Harga
                        </small>

                        <strong className="fs-6">
                          {formatRupiah(item.product.price)}
                        </strong>
                      </div>

                      {/* QUANTITY */}
                      <div className="col-md-3">
                        <small className="text-secondary d-block mb-2">
                          Jumlah
                        </small>

                        <input
                          type="number"
                          className="form-control form-control-lg"
                          defaultValue={item.quantity}
                          min="1"
                          max={item.product.stock}
                          onBlur={(e) =>
                            handleUpdateQuantity(
                              item.product.id,
                              e.target.value
                            )
                          }
                        />
                      </div>

                      {/* DELETE */}
                      <div className="col-md-2 text-md-end">
                        <button
                          type="button"
                          className="btn btn-link text-danger text-decoration-none p-0"
                          onClick={() =>
                            handleRemove(item.product.id)
                          }
                        >
                          🗑️ Hapus Item
                        </button>
                      </div>
                    </div>

                    {/* SUBTOTAL */}
                    <div className="text-end mt-4 pt-3 border-top">
                      <small className="text-secondary d-block mb-1">
                        Subtotal
                      </small>

                      <div className="fw-bold fs-5 text-primary">
                        {formatRupiah(item.subtotal)}
                      </div>
                    </div>
                  </div>
                ))}

              </div>
            </div>

            {/* SUMMARY */}
            <div className="col-lg-4">
              <div
                className="bg-white rounded-4 shadow-sm border p-5 sticky-lg-top"
                style={{ top: "90px" }}
              >
                <h4 className="fw-bold mb-4">
                  Ringkasan Belanja
                </h4>

                <div className="d-flex justify-content-between mb-4">
                  <span className="text-secondary">
                    Jumlah item
                  </span>

                  <strong className="fs-5">
                    {totalQuantity}
                  </strong>
                </div>

                <div className="border-top pt-4">
                  <div className="d-flex justify-content-between align-items-center">
                    <span className="fw-semibold">
                      Total
                    </span>

                    <strong className="fs-3 text-primary">
                      {formatRupiah(total)}
                    </strong>
                  </div>
                </div>

                <Link
                  to="/checkout"
                  className="btn btn-primary w-100 rounded-pill py-3 mt-5"
                >
                  Lanjut Checkout
                </Link>

                <Link
                  to="/products"
                  className="btn btn-light w-100 rounded-pill py-3 mt-2"
                >
                  Lanjut Belanja
                </Link>
              </div>
            </div>

          </div>
        )}
      </div>
    </Layout>
  );
}