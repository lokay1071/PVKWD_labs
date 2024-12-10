import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Product } from './product.entity';
import { Category } from '../categories/category.entity';
import { DeleteResult, Repository } from 'typeorm';
import {
  IPaginationOptions,
  paginate,
  Pagination,
} from 'nestjs-typeorm-paginate';

@Injectable()
export class ProductsService {
  constructor(
    @InjectRepository(Product)
    private repository: Repository<Product>,
    @InjectRepository(Category)
    private categoryRepository: Repository<Category>,
  ) {}

  public async create(productData: Product): Promise<Product> {
    const category = await this.categoryRepository.findOneBy({
      id: productData.categoryId,
    });

    if (!category) {
      throw new NotFoundException(
        `Category with id ${productData.categoryId} not found.`,
      );
    }

    return this.repository.save(productData);
  }

  public findAll(): Promise<Product[]> {
    return this.repository.find();
  }

  public async findOne(id: number): Promise<Product | null> {
    const product = await this.repository.findOneBy({ id });

    if (!product) {
      throw new NotFoundException(`Product with id ${id} not found.`);
    }

    return product;
  }

  async update(id: number, productData: Product): Promise<Product> {
    const product = await this.repository.findOneBy({ id });
    if (!product) {
      throw new Error(`Product with ID ${id} not found.`);
    }

    const category = await this.categoryRepository.findOneBy({
      id: productData.categoryId,
    });

    if (!category) {
      throw new NotFoundException(
        `Category with id ${productData.categoryId} not found.`,
      );
    }

    Object.assign(product, productData);
    return this.repository.save(product);
  }

  public async remove(id: number): Promise<DeleteResult> {
    const product = await this.repository.findOneBy({ id });

    if (!product) {
      throw new NotFoundException(`Product with id ${id} not found.`);
    }

    return this.repository.delete(id);
  }

  public paginate(options: IPaginationOptions): Promise<Pagination<Product>> {
    return paginate<Product>(this.repository, options);
  }

  async findByCategory(categoryId: string): Promise<Product[]> {
    return this.repository.find({ where: { categoryId: +categoryId } });
  }
}
