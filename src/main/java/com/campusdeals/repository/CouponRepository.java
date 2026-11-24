package com.campusdeals.repository;

import com.campusdeals.domain.Coupon;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Modifying;
import org.springframework.data.jpa.repository.Query;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

public interface CouponRepository extends JpaRepository<Coupon, Long> {

    String ACTIVE = "c.active = true and c.store.active = true";

    @Query("select c from Coupon c where " + ACTIVE + " order by c.sortOrder, c.id")
    Page<Coupon> findActive(Pageable pageable);

    @Query("select c from Coupon c where " + ACTIVE + " and c.featured = true order by c.sortOrder, c.id limit 8")
    List<Coupon> findFeatured();

    @Query("select c from Coupon c where " + ACTIVE + " and (lower(c.title) like lower(concat('%', :q, '%'))"
            + " or lower(c.store.name) like lower(concat('%', :q, '%'))) order by c.sortOrder")
    Page<Coupon> search(String q, Pageable pageable);

    @Query("select distinct c from Coupon c join c.store.categories cat where " + ACTIVE + " and cat.id = :categoryId"
            + " order by c.sortOrder")
    List<Coupon> findActiveInCategory(Long categoryId);

    @Query("select count(c) from Coupon c where " + ACTIVE)
    long countActive();

    List<Coupon> findTop5ByOrderByCreatedAtDesc();

    Page<Coupon> findAllByOrderBySortOrderAscIdAsc(Pageable pageable);

    @Modifying
    @Transactional
    @Query("update Coupon c set c.usedCount = c.usedCount + 1 where c.id = :id")
    void incrementUsed(Long id);
}
