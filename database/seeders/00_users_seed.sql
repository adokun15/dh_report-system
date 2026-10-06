START TRANSACTION;

INSERT INTO users (user_id, role, name, photo)
VALUES
    ('11111111-1111-1111-1111-111111111111', 'admin', 'Engr Kehinde', NULL),
    ('22222222-2222-2222-2222-222222222222',
         'staff', 'Mr Seun', NULL),
    ('33333333-3333-3333-3333-333333333333', 'staff', 'Kego', NULL),
    ('44444444-4444-4444-4444-444444444444', 'staff', 'Mr Wale', NULL),
    ('55555555-5555-5555-5555-555555555555', 'staff', 'Mr DavidSon', NULL);

INSERT INTO auth (user_id, email, password_hash)
VALUES
    (
        '11111111-1111-1111-1111-111111111111',
        'admin@lassra.com',
        '$2y$10$.fPlzmGoOu39NVLdMd87p.OTtyEX3khujFvcC9.a3pnnYPxSIP8RK'
    ),
    (
        '22222222-2222-2222-2222-222222222222',
        'seun@lassra.com',
        '$2y$10$.fPlzmGoOu39NVLdMd87p.OTtyEX3khujFvcC9.a3pnnYPxSIP8RK'
    ),
    (
        '33333333-3333-3333-3333-333333333333',
        'kego@lassra.com',
        '$2y$10$.fPlzmGoOu39NVLdMd87p.OTtyEX3khujFvcC9.a3pnnYPxSIP8RK'
    ),
    (
        '44444444-4444-4444-4444-444444444444',
        'wale@lassra.com',
        '$2y$10$.fPlzmGoOu39NVLdMd87p.OTtyEX3khujFvcC9.a3pnnYPxSIP8RK'
    ),
    (
        '55555555-5555-5555-5555-555555555555',
        'davidson@lassra.com',
        '$2y$10$.fPlzmGoOu39NVLdMd87p.OTtyEX3khujFvcC9.a3pnnYPxSIP8RK'
    );
rollback;