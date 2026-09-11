import { useEffect, useState } from "react";
import { useSearchParams, Link } from "react-router-dom";
import axios from "../../api/axios";
import Layout from "../Layouts";

export default function Products() {
  const [searchParams, setSearchParams] = useSearchParams();
  const initialStoreId = searchParams.get("store_id") ?? "";

  const [allProducts, setAllProducts] = useState([]);
  const [stores, setStores] = useState([]);
  const [loading, setLoading] = useState(true);

  const [keyword, setKeyword] = useState("");
  const [storeId, setStoreId] = useState(initialStoreId);

  useEffect(() => {
    setLoading(true);
    Promise.all([axios.get("/products"), axios.get("/stores")])
      .then(([resProducts, resStores]) => {
        setAllProducts(resProducts.data.data ?? resProducts.data);
        setStores(resStores.data.data ?? resStores.data);
      })
      .catch((err) => console.error(err))
      .finally(() => setLoading(false));
  }, []);

  const formatRupiah = (value) =>
    "Rp " + Number(value ?? 0).toLocaleString("id-ID");

  const filteredProducts = allProducts.filter((p) => {
    const matchKeyword = p.name
      ?.toLowerCase()
      .includes(keyword.toLowerCase());
    const matchStore = storeId ? String(p.store_id) === String(storeId) : true;
    return matchKeyword && matchStore;
  });

  const handleSearch = (e) => {
    e.preventDefault();
    setSearchParams(storeId ? { store_id: storeId } : {});
  };

  const storeName = (id) => stores.find((s) => String(s.id) === String(id))?.name;

  return (
    <Layout>
      <div className="container py-4">

        {/* HERO */}
        <div
          className="position-relative rounded-4 overflow-hidden mb-5 p-5 text-white d-flex align-items-center justify-content-between"
          style={{
            minHeight: "270px",
            background: "linear-gradient(120deg, #7c3aed, #a855f7, #4f46e5)",
            boxShadow: "0 15px 30px rgba(79, 70, 229, .18)",
          }}
        >
          <div style={{ position: "relative", zIndex: 2, maxWidth: "650px" }}>
            <span
              className="d-inline-block mb-3 px-3 py-2 rounded-pill"
              style={{
                border: "1px solid rgba(255,255,255,.7)",
                fontSize: "13px",
                fontWeight: 600,
              }}
            >
              Belanja lebih mudah
            </span>
            <h1 className="fw-bold mb-3">Temukan Produk Favoritmu</h1>
            <p className="mb-4" style={{ color: "rgba(255,255,255,.85)" }}>
              Temukan berbagai produk pilihan dari toko-toko yang tersedia di
              TokoKita.
            </p>
            <Link to="/" className="btn btn-light rounded-pill px-4 fw-semibold">
              Mulai Belanja
            </Link>
          </div>

          <div
            className="d-none d-md-flex flex-column align-items-center justify-content-center rounded-circle"
            style={{
              width: "120px",
              height: "120px",
              background: "rgba(255,255,255,.10)",
              position: "relative",
              zIndex: 2,
            }}
          >
            <div className="fw-bold" style={{ fontSize: "42px", lineHeight: 1 }}>
              TK
            </div>
            <span style={{ color: "rgba(255,255,255,.8)", fontSize: "13px" }}>
              TokoKita
            </span>
          </div>
        </div>

        {/* SEARCH */}
        <div className="bg-white rounded-4 shadow-sm border p-4 mb-4">
          <h3 className="fw-bold mb-1">Cari Produk</h3>
          <p className="text-secondary mb-4">
            Gunakan pencarian atau pilih toko untuk menemukan produk.
          </p>

          <form className="row g-3 align-items-end" onSubmit={handleSearch}>
            <div className="col-md-5">
              <label className="form-label small">Nama Produk</label>
              <input
                type="text"
                className="form-control form-control-lg"
                placeholder="Cari produk..."
                value={keyword}
                onChange={(e) => setKeyword(e.target.value)}
              />
            </div>

            <div className="col-md-4">
              <label className="form-label small">Toko</label>
              <select
                className="form-select form-select-lg"
                value={storeId}
                onChange={(e) => setStoreId(e.target.value)}
              >
                <option value="">Semua toko</option>
                {stores.map((s) => (
                  <option key={s.id} value={s.id}>{s.name}</option>
                ))}
              </select>
            </div>

            <div className="col-md-3">
              <button type="submit" className="btn btn-primary btn-lg w-100">
                Cari
              </button>
            </div>
          </form>
        </div>

        {/* PRODUCT GRID */}
        <div>
          <div className="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h3 className="fw-bold mb-1">Produk Pilihan</h3>
              <p className="text-secondary mb-0">
                Pilihan produk yang tersedia saat ini.
              </p>
            </div>
            <span className="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
              {filteredProducts.length} Produk
            </span>
          </div>

          {loading ? (
            <div className="bg-white rounded-4 border text-center py-5 text-secondary">
              Memuat produk...
            </div>
          ) : filteredProducts.length === 0 ? (
            <div className="bg-white rounded-4 border text-center py-5 text-secondary">
              Belum ada produk yang cocok.
            </div>
          ) : (
            <div className="row g-3">
              {filteredProducts.map((product) => (
                <div className="col-12 col-sm-6 col-lg-3" key={product.id}>
                  <div className="card h-100 border rounded-4 overflow-hidden">
                    <div
                      className="d-flex align-items-center justify-content-center"
                      style={{
                        height: "180px",
                        background:
                          "radial-gradient(circle at 80% 15%, #e9e4ff 0, #e9e4ff 20%, transparent 21%), radial-gradient(circle at 10% 90%, #e1e7ff 0, #e1e7ff 22%, transparent 23%), #f1efff",
                      }}
                    >
                      {product.image ? (
                        <img
                          src={product.image}
                          alt={product.name}
                          className="w-100 h-100"
                          style={{ objectFit: "cover" }}
                        />
                      ) : (
                        <div
                          className="d-flex align-items-center justify-content-center rounded-3 bg-white shadow-sm"
                          style={{ width: "70px", height: "70px" }}
                        >
                          <span className="fw-bold fs-4 text-primary">
                            {product.name?.charAt(0).toUpperCase()}
                          </span>
                        </div>
                      )}
                    </div>

                    <div className="card-body">
                      <small className="text-secondary d-block mb-1">
                        {storeName(product.store_id) ?? "TokoKita"}
                      </small>
                      <h6 className="fw-bold" style={{ minHeight: "42px" }}>
                        {product.name}
                      </h6>
                      <div className="text-primary fw-bold fs-6 mt-2">
                        {formatRupiah(product.price)}
                      </div>
                      <div className="text-secondary small mt-1 mb-3">
                        Stok tersedia: <strong className="text-success">{product.stock}</strong>
                      </div>
                      <Link
                        to={`/products/${product.id}`}
                        className="btn btn-primary w-100 rounded-pill"
                      >
                        Lihat Detail
                      </Link>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </Layout>
  );
}