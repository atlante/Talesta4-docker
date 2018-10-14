for FILE in ./*/*
do
        [ -f "$FILE" ] || continue

        iconv -c -t UTF8 -f ASCII "$FILE" > /tmp/$$ && cat /tmp/$$ > "$FILE"
done

rm -f /tmp/$$
