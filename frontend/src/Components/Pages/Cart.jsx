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
      <div className="container py-4">
        <div className="mb-4">
          <h1 className="fw-bold mb-1">Keranjang Belanja</h1>
          <p className="text-secondary mb-0">
            Periksa kembali produk sebelum melanjutkan ke checkout.
          </p>
        </div>

        {items.length === 0 ? (
          <div className="bg-white rounded-4 shadow-sm border p-5 text-center">
            <div
              className="mx-auto mb-4 rounded-circle bg-light d-flex align-items-center justify-content-center"
              style={{ width: "80px", height: "80px" }}
            >
              <span className="fs-3 fw-bold text-secondary">0</span>
            </div>

            <h3 className="fw-bold mb-2">Keranjang masih kosong</h3>
            <p className="text-secondary mb-4">
              Belum ada produk yang kamu tambahkan ke keranjang.
            </p>

            <Link to="/products" className="btn btn-primary rounded-pill px-4">
              Mulai Belanja
            </Link>
          </div>
        ) : (
          <div className="row g-4">
            {/* CART ITEMS */}
            <div className="col-lg-8">
              <div className="bg-white rounded-4 shadow-sm border overflow-hidden">
                {items.map((item) => (
                  <div className="p-4 border-bottom" key={item.id}>
                    <div className="row align-items-center g-4">
                      {/* PRODUCT */}
                      <div className="col-md-5">
                        <div className="d-flex align-items-center gap-3">
                          <div
                            className="flex-shrink-0 rounded-3 d-flex align-items-center justify-content-center"
                            style={{
                              width: "80px",
                              height: "80px",
                              background:
                                "linear-gradient(135deg, #ede9fe, #e0e7ff)",
                            }}
                          >
                            <span className="fs-3 fw-bold text-primary">
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

                      {/* PRICE */}
                      <div className="col-md-2">
                        <small className="text-secondary d-block mb-1">
                          Harga
                        </small>
                        <strong>{formatRupiah(item.product.price)}</strong>
                      </div>

                      {/* QUANTITY */}
                      <div className="col-md-3">
                        <small className="text-secondary d-block mb-1">
                          Jumlah
                        </small>
                        <input
                          type="number"
                          className="form-control"
                          defaultValue={item.quantity}
                          min="1"
                          max={item.product.stock}
                          onBlur={(e) =>
                            handleUpdateQuantity(item.product.id, e.target.value)
                          }
                        />
                      </div>

                      {/* DELETE */}
                      <div className="col-md-2 text-md-end">
                        <button
                          type="button"
                          className="btn btn-link text-danger text-decoration-none p-0"
                          onClick={() => handleRemove(item.product.id)}
                        >
                          🗑️ Hapus Item
                        </button>
                      </div>
                    </div>

                    {/* SUBTOTAL */}
                    <div className="text-end mt-3">
                      <small className="text-secondary">Subtotal</small>
                      <div className="fw-bold text-primary">
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
                className="bg-white rounded-4 shadow-sm border p-4 sticky-lg-top"
                style={{ top: "90px" }}
              >
                <h5 className="fw-bold mb-4">Ringkasan Belanja</h5>

                <div className="d-flex justify-content-between mb-3">
                  <span className="text-secondary">Jumlah item</span>
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

                <Link
                  to="/checkout"
                  className="btn btn-primary w-100 rounded-pill py-3 mt-4"
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