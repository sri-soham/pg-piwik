CREATE OR REPLACE FUNCTION CRC32(p_value TEXT) RETURNS int AS $$
DECLARE
	v_hash BYTEA;
BEGIN
	v_hash := DECODE(MD5(p_value),'hex');
	RETURN (get_byte(v_hash,0) << 24) + (get_byte(v_hash,1) << 16) + (get_byte(v_hash,2) << 8) + get_byte(v_hash,3);
END;
$$ LANGUAGE plpgsql;