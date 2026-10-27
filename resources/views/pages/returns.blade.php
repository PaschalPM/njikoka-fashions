<x-layouts.app title="Returns" body-class="bg-white">
    <x-page-hero title="Returns" />

    <section class="bg-white py-[30px] lg:pt-[60px] lg:pb-[80px]">
        <article class="prose-policy container-site lg:px-10">
            <h2>Return &amp; Exchange Policy</h2>
            <p>At <strong>Njikoka Fashions LLC</strong>, we want you to love what you ordered. If something isn’t right, we’re here to help. Please read our return and exchange policy below.</p>

            <h3>Return Window</h3>
            <ul>
                <li>You may request a return or exchange within <strong>7 days</strong> of receiving your order.</li>
                <li>To be eligible, items must be <strong>unworn, unwashed, and unaltered</strong>, with all original tags and packaging intact.</li>
                <li>Proof of purchase (order number or invoice) is required.</li>
            </ul>

            <h3>Eligible Items</h3>
            <p>Items can be returned or exchanged if:</p>
            <ul>
                <li>You received the wrong item, size, or color</li>
                <li>The item is defective or damaged</li>
                <li>The item doesn’t fit (size exchange)</li>
            </ul>

            <h3>Non-Returnable Items</h3>
            <p>For hygiene and safety reasons, the following cannot be returned or exchanged:</p>
            <ul>
                <li>Innerwear, lingerie, socks, and swimwear</li>
                <li>Face masks, scarves, and accessories (unless defective)</li>
                <li>Items marked <strong>Final Sale</strong> or <strong>Clearance</strong></li>
                <li>Customized or tailored items</li>
                <li>Items that have been worn, washed, altered, or have perfume, stains, or odors</li>
                <li>Items without original tags</li>
            </ul>

            <h3>Size Exchanges</h3>
            <ul>
                <li>Size exchanges are subject to stock availability.</li>
                <li>If your size is unavailable, you may choose a different item of equal value, or receive store credit or a refund.</li>
                <li>The first exchange is free of charge for the customer. Further exchanges will incur shipping fees.</li>
            </ul>

            <h3>Damaged, Defective, or Wrong Items</h3>
            <p>If you receive a damaged, defective, or incorrect item:</p>
            <ul>
                <li>Contact us within <strong>48 hours</strong> of delivery with your order number and clear photos.</li>
                <li>We will arrange a free pickup and send a replacement, or issue a full refund.</li>
            </ul>

            <h3>How to Request a Return or Exchange</h3>
            <ol>
                <li>Email us at <a href="mailto:{{ config('store.email') }}">{{ config('store.email') }}</a> or calls us at <a href="tel:3019680567">301-968-0567</a> with your order number.</li>
                <li>Tell us the item(s), the reason for return, and whether you’d like an exchange, store credit, or refund. Attach photos if the item is damaged.</li>
                <li>Once approved, we will share return instructions and the return address.</li>
                <li>Pack the item securely in its original packaging with tags attached.</li>
                <li>Ship the item back (or hand it to our courier if pickup is arranged).</li>
            </ol>

            <h3>Return Shipping Costs</h3>
            <ul>
                <li><strong>Change of mind or wrong size ordered:</strong> the customer pays return shipping.</li>
                <li><strong>Our error (wrong, damaged, or defective item):</strong> we cover return shipping.</li>
                <li>Original shipping charges are non-refundable, except when the return is due to our error.</li>
            </ul>

            <h3>Refunds</h3>
            <ul>
                <li>Once we receive and inspect your return, we will notify you of the approval or rejection of your refund.</li>
                <li>Approved refunds are processed within <strong>5-7 business days</strong>, to your original payment method.</li>
                <li>For Cash on Delivery orders, refunds are issued via bank transfer or store credit (your choice).</li>
                <li>Your bank or card issuer may take extra time to post the refund.</li>
                <li>Items that fail inspection (worn, washed, missing tags) will be sent back to you, and no refund will be issued.</li>
            </ul>

            <h3>Store Credit</h3>
            <p>You may choose store credit instead of a refund. Store credit is issued as a coupon code valid for 90 days and can be used on any future order.</p>

            <h3>Sale Items</h3>
            <ul>
                <li>Sale items can be exchanged but not refunded, unless defective or damaged.</li>
                <li>Items bought with a discount code are refunded at the amount actually paid.</li>
            </ul>

            <h3>Cancelled Orders</h3>
            <p>Orders can be cancelled within 12 hours of placement, provided they haven’t been shipped. After shipping, the standard return policy applies.</p>

            <h3>International Orders</h3>
            <p>International customers are responsible for return shipping costs and any customs duties or taxes. Original duties and taxes are non-refundable.</p>
        </article>
    </section>
</x-layouts.app>
