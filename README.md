# Track123 OpenAPI Laravel Package

`thank-song/track123` 是一个用于接入 **Track123 快递轨迹 OpenAPI** 的 Laravel 工具包，支持物流单号注册、查询、轨迹同步及队列处理，适用于中大型 Laravel 项目。

---

## ⚙️ 环境要求

- PHP >= 8.0
- Laravel >= 9.0
- MySQL >= 8.0

---

## 📦 安装

通过 Composer 安装：

```bash
composer require thank-song/track123
```

---

## ⚙️ 配置

在项目 `.env` 文件中添加以下配置：

```env
TRACK123_API_TOKEN=xxxxxxxxxxxxxxxxxxxxxxxxxxxx   # Track123 API Token（必填）
TRACK123_API_URL=https://api.track123.com/gateway/open-api/  # API 地址（可选）
```

> ⚠️ `TRACK123_API_TOKEN` 为必填项，否则接口请求将失败。

---

## 🗄 数据库迁移

本包内置所需的数据表迁移文件，直接执行：

```bash
php artisan migrate
```

---

## 🧠 核心概念

| 模型 | 说明 |
|---|---|
| Courier | 快递商信息（由 Track123 提供） |
| Tracking | 物流单号主体 |
| TrackingDetail | 轨迹节点明细 |
| TrackingExtraInfo | 额外轨迹信息 |
| LocalLogistic | 本地物流补充信息 |

---

## 🚀 使用说明

### 1️⃣ 获取 Courier（快递商）列表

```bash
php artisan track123:get-couriers
```

该命令将：
- 从 Track123 API 拉取最新快递商
- 同步更新本地 couriers 表
- 刷新缓存（如有）

---

### 2️⃣ 注册物流单号（Tracking）

#### 2.1 使用工厂静态方法注册（同步）

```php
use ThankSong\Track123\Track123;

try {
    $trackings = [
        ['trackNo' => '886000000087', 'courierCode' => 'fedex'],
        ['trackNo' => '886000000007', 'courierCode' => 'fedex'],
        ['trackNo' => '886000000003', 'courierCode' => 'fedex'],
    ];

    $res = Track123::register($trackings);

    var_dump($res->getAccepted());          // 注册成功的单号
    var_dump($res->getRejected());          // 注册失败的单号
    var_dump($res->getRejectedMessages());  // 失败原因
} catch (\Throwable $e) {
    var_dump($e->getMessage());
}
```

---

#### 2.2 使用队列 Job 注册（推荐生产环境）

```php
use ThankSong\Track123\Jobs\RegisterTrackingJob;

$trackings = [
    ['trackNo' => '886000000087', 'courierCode' => 'fedex'],
    ['trackNo' => '886000000007', 'courierCode' => 'fedex'],
    ['trackNo' => '886000000003', 'courierCode' => 'fedex'],
];

RegisterTrackingJob::dispatch($trackings)->onQueue('default');
```

---

### 3️⃣ 查询物流轨迹

#### 3.1 使用工厂静态方法查询

```php
use ThankSong\Track123\Track123;

try {
    // 单个单号查询
    $res = Track123::query('886000000003');

    // 批量查询示例
    /*
    $trackings = [
        ['trackNo' => '886000000007'],
        ['trackNo' => '886000000087'],
    ];
    $res = Track123::queryTrackings($trackings);
    */

    var_dump($res->getAccepted());          // 查询成功
    var_dump($res->getRejected());          // 查询失败
    var_dump($res->getRejectedMessages());  // 失败原因
} catch (\Throwable $e) {
    var_dump($e->getMessage());
}
```

---

#### 3.2 使用 Artisan 命令查询（并同步模型数据）

```bash
php artisan track123:query "886000000003"
```

该命令将：
- 向 Track123 查询最新轨迹
- 同步更新 Tracking / Detail / ExtraInfo 等模型

---

## 🧰 Artisan 命令汇总

| 命令 | 说明 |
|---|---|
| `track123:get-couriers` | 同步快递商列表 |
| `track123:query {tracking_no}` | 查询并更新物流轨迹 |

---

### 4️⃣ 修改快递商信息
```php
try {
    $res= Track123::changeCarrier(oldCourierCode: 'mng-kargo',trackNo: '886957347096',newCourierCode: 'fedex');
    var_dump($res->getMessage());          // 响应信息
} catch (\Throwable $e) {
    var_dump($e->getMessage());
}
```

---
## 设置webhook
your-domin.com/api/track123/webhook

## ❓ 常见问题

### Q：数据是 UTC 还是本地时间？

- Track123 返回时间通常为 **UTC**
- 建议按 UTC 存库
- 展示时可使用 Carbon 转换时区（例如 America/Los_Angeles）

### Q：是否支持队列？

- 支持
- 注册与查询均可通过 Job 执行，适合高并发 / 批量场景

---

## 📄 License

MIT License
